interface Workflow3a { 
    Person alex = new Person("Alex", false);
    Person chris = new Person("Chris", false);
    Person mike = new Person("Mike", false);
    Person dave = new Person("Dave", true);

    static void main(String[] args) {
        System.out.println(workflow1()); // ABGELEHNT
        System.out.println(workflow2()); // GENEHMIGT
        System.out.println(workflow3()); // MITGEZEICHNET
        System.out.println(workflow4()); // GENEHMIGT
    }

    static Antrag workflow1() {
        System.out.println("workflow1");

        return Antrag
            .stelleAntrag(alex)
            .sachbearbeite(chris, false)
            .sachbearbeite(mike, true) // Fehler, da Antrag bereits im Abgelehnt-Zustand
            .sachbearbeite(dave, true);
    }

    static Antrag workflow2() {
        System.out.println("workflow2");

        return Antrag
            .stelleAntrag(alex)
            .sachbearbeite(chris, true)
            .sachbearbeite(mike, true)
            .sachbearbeite(dave, true);
    }

    static Antrag workflow3() {
        System.out.println("workflow3");

        return Antrag
            .stelleAntrag(alex)
            .sachbearbeite(chris, true)
            .sachbearbeite(mike, true);
    }

    static Antrag workflow4() {
        System.out.println("workflow4");

        return Antrag
            .stelleAntrag(alex)
            .sachbearbeite(chris, true)
            .sachbearbeite(dave, true)
            .sachbearbeite(mike, true); // Fehler, da keine Mitzeichnung nach Genehmigung möglich
    }
}

record Person(String name, boolean isGenehmiger) {}

/*
 * Zustandsbasierter Workflow wird im Wesentlichen durch Zustände eines Objekts
 * sowie Input mit zustandsabhängigen Folgezuständen bestimmt
 */
enum Antrag {
    /*
     * Zustände mit Transitionslogik
     */
    GESTELLT,
    MITGEZEICHNET,
    GENEHMIGT,
    ABGELEHNT;

    /*
     * Funktionale Schnittstelle
     */

    Antrag sachbearbeite(Person p, boolean accept) {
        return switch(this) {
            case GESTELLT, MITGEZEICHNET -> check(p, accept);
            case GENEHMIGT, ABGELEHNT -> error(p, this);
            default -> throw new UnsupportedOperationException("Ungültiger Antragsstatus!");
        };
    }

    static Antrag stelleAntrag(Person antragsteller) { return GESTELLT; }



    /*
     * Hilfsfunktionen
     */

    protected static Antrag check(Person p, boolean accept) {
        if (!accept) return ABGELEHNT;
            
        return p.isGenehmiger() ? GENEHMIGT : MITGEZEICHNET;
    }

    static Antrag error(Person p, Antrag status) {
        System.out.println(String.format(
            "Antrag bereits %s! Keine Änderung durch %s möglich!",
            status == GENEHMIGT ? "genehmigt" : "abgelehnt", p.name()));

        return status;
    }
}
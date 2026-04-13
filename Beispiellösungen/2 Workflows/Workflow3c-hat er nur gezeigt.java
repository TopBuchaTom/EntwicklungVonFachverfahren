interface Workflow3c { 
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

        return Antrag.stelleAntrag(alex)
            .sachbearbeite(chris, false)
            .sachbearbeite(mike, true) // Fehler, da Antrag bereits im Abgelehnt-Zustand
            .sachbearbeite(dave, true);
    }

    static Antrag workflow2() {
        System.out.println("workflow2");

        return Antrag.stelleAntrag(alex)
            .sachbearbeite(chris, true)
            .sachbearbeite(mike, true)
            .sachbearbeite(dave, true);
    }

    static Antrag workflow3() {
        System.out.println("workflow3");

        return Antrag.stelleAntrag(alex)
            .sachbearbeite(chris, true)
            .sachbearbeite(mike, true);
    }

    static Antrag workflow4() {
        System.out.println("workflow4");

        return Antrag.stelleAntrag(alex)
            .sachbearbeite(chris, true)
            .sachbearbeite(dave, true)
            .sachbearbeite(mike, true); // Fehler, da keine Mitzeichnung nach Genehmigung möglich
    }
}

record Person(String name, boolean isGenehmiger) {}

interface Antrag {
    Antrag sachbearbeite(Person p, boolean accept);

    static Antrag GESTELLT = (p, accept) -> check(p, accept);
    static Antrag MITGEZEICHNET = (p, accept) -> check(p, accept);
    static Antrag GENEHMIGT = (p, __) -> error(p, "genehmigt");
    static Antrag ABGELEHNT = (p, __) -> error(p, "abgelehnt");
    
    static Antrag stelleAntrag(Person antragsteller) {
        return GESTELLT;
    }

    static Antrag check(Person p, boolean accept) {
        if (!accept) return ABGELEHNT;
        return p.isGenehmiger() ? GENEHMIGT : MITGEZEICHNET;
    }

    static Antrag error(Person p, String status) {
        System.out.println(String.format(
            "Antrag bereits %s! Keine Änderung durch %s möglich!",
            status, p.name()));
        return status.equals("genehmigt") ? GENEHMIGT : ABGELEHNT;
    }
}
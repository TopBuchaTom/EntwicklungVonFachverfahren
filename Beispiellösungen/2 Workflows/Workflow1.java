interface Workflow1 {
    static void main(String[] args) {
        final var bescheid = Actions.erstelleBescheid();
        Actions.stelleBescheidInPortal(bescheid);
        Actions.versendeBescheidPerMail(bescheid);
    }
}

interface Actions {  
    static Bescheid erstelleBescheid() {
        return new Bescheid(1, "Test-Bescheid");
    };

    static void stelleBescheidInPortal(Bescheid bescheid) {
        /* Bescheid in Portal stellen */
    }
    
    static void versendeBescheidPerMail(Bescheid bescheid) {
        /* E-Mail-Versand von Bescheid */
    };   
}

record Bescheid(int id, String data) {}
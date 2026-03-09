interface Workflow2 {
    static void main(String[] args) {
        final var bescheid = Actions.stelleAntrag();
        
        // Negative Logik, da effizienter
        if (!Actions.mitzeichneAntrag(bescheid))
            return;

        if (!Actions.genehmigeAntrag(bescheid))
            return;

        System.out.println("Antrag wurde angenommen");
    }
}

interface Actions {  
    static Bescheid stelleAntrag() {
        return new Bescheid(1, "Test-Bescheid");
    };

    static boolean mitzeichneAntrag(Bescheid bescheid) {
        return Math.random() < 0.5;
    }
    
    static boolean genehmigeAntrag(Bescheid bescheid) {
        return Math.random() < 0.5;
    };
}

record Antrag(int id, String data) {}
// CRUD
// - only latest state is stored

class Travel {
    constructor(title, location, start, end, amount, details) {
        this.title = title;
        this.location = location;
        this.start = start;
        this.end = end;
        this.amount = amount;
        this.details = details;
    }
}

//oder:
//function Travel(title, location, start, end, amount, details) {
//    return Object.apply(this, { title, location, start, end, amount, details });
//}
//von mir auskommentiert:
//In JavaScript darf Travel nicht zweimal im selben Scope deklariert werden.
//Klasse wird in JS als funktion umgesetzt

class TravelRepository {
    #travels = [];

    getTravel(id, revisionId = undefined) {
        return this.#travels[id];
    }

    getRevisions(id, minRevisionId, maxRevisionId) {
        return [this.getTravel(id)];
    }

    insertTravel(travel) {
        return this.#travels.push(travel) - 1;
    }

    updateTravel(id, travel) {
        this.#travels[id] = travel;

        return true;
    }

    deleteTravel(id) {
        delete this.#travels[id];
    }
}

const travelRepository = new TravelRepository();



// Wird aufgerufen, wenn Antragsteller neuen Reiseantrag über GUI erstellt
function createSomeTravelRequest() {
    const travel = new Travel(
        "Nacht der Wissenschaft",
        "Hof",
        new Date(20, 4, 2024, 10, 30),
        new Date(20, 4, 2024, 18, 30),
        120.99,
        "Bahnticket 100, ÖPNV 20,99",
    );

    return travelRepository.insertTravel(travel);
}

function review1OfSomeTravelRequest(travelId) {
    console.log("**********")   //von mir
    const travel = travelRepository.getTravel(travelId);
    console.log(travel)         //von mir
    console.log("**********")   //von mir
    travel.location = "Hochschule Hof";

    travelRepository.updateTravel(travelId, travel);
}

function review2OfSomeTravelRequest(travelId) {
    const travel = travelRepository.getTravel(travelId);
    travel.amount = 100.00;
    travel.details = "Bahnticket 100, ÖPNV inklusive";

    travelRepository.updateTravel(travelId, travel);
}

function review3fSomeTravelRequest(travelId) {
    const travel = travelRepository.getTravel(travelId);

    travelRepository.deleteTravel(travelId, travel);
}

function outputTravel(travelId, eventId) {
    console.log(`Travel ${travelId} (revision ${eventId ?? "current"}):\n`,
        travelRepository.getTravel(travelId, eventId), '\n');
}

function outputTravelRevisions(travelId, minRevisionId, maxRevisionId) {
    console.log(`Travel ${travelId} - Events between revision ${minRevisionId ?? "initial"} and ${maxRevisionId ?? "current"}:`,
        travelRepository.getRevisions(travelId, minRevisionId, maxRevisionId));
}

function someSampleWorkflow() {
    // Schritt 1: Antragsteller erzeugt neuen Antrag
    const travelId = createSomeTravelRequest();

    // Schritt 2: Vorgesetzter prüft Antrag und präzisiert Ort
    review1OfSomeTravelRequest(travelId);

    // Schritt 3: Reiseservicestelle kürzt erstattungsfähigen Höchstbetrag
    review2OfSomeTravelRequest(travelId);

    // Schritt 4: Referatsleiter lehnt Reiseantrag ab, da anderer Temin
    review3fSomeTravelRequest(travelId);

    return travelId;
}

const travelId = someSampleWorkflow();

outputTravel(travelId); // Up to date
// Travel 0 (revision current):
//  undefined

outputTravelRevisions(travelId); // Alle Events ausgeben
// Travel 0 - Events between revision initial and current: [ undefined ]
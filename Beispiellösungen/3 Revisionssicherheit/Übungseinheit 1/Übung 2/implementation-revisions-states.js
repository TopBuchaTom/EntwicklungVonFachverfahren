// States:
// - großer Speicherverbrauch, da auch bei keiner Änderung für jede Revision Daten dupliziert werden
// - keine Persistenz von Datenänderungen, d.h. müssen bei Bedarf jeweils neu ermittelt werden
// - schnellere Performance zur Darstellung von bestimmten Zustand, da direkt abrufbar
// - Implementierung gestaltet sich relativ einfach


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

// function Travel(title, location, start, end, amount, details) {
//     return Object.apply(this, { title, location, start, end, amount, details });
// }

class TravelRepository {
    #travels = [];

    getTravel(id, revisionId = undefined) {
        const result = revisionId != null
            ? this.#travels[revisionId]
            : this.#travels.findLast(travel => travel.originalId == id) ?? this.#travels[id];

        return {...result};
    }

    getRevisions(id, minRevisionId, maxRevisionId) {
        return this.#travels.slice(minRevisionId, maxRevisionId).filter((travel, index) => index == id || travel.originalId == id);
    }

    insertTravel(travel) {
        return this.#travels.push(travel) - 1;
    }

    updateTravel(id, travel) {
        const updatedTravel = { ...travel, originalId: id };

        return this.insertTravel(updatedTravel);
    }

    deleteTravel(id) {
        const deletedTravel = { ...new Travel(), originalId: id };

        return this.insertTravel(deletedTravel);
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
    const travel = travelRepository.getTravel(travelId);
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

function outputTravel(travelId, revisionId) {
    console.log(`Travel ${travelId} (revision ${revisionId ?? "current"}):\n`,
        travelRepository.getTravel(travelId, revisionId), '\n');
}

function outputTravelRevisions(travelId, minRevisionId, maxRevisionId) {
    console.log(`Travel ${travelId} - States between revision ${minRevisionId ?? "initial"} and ${maxRevisionId ?? "current"}:`,
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
//  {
//   title: undefined,
//   location: undefined,
//   start: undefined,
//   end: undefined,
//   amount: undefined,
//   details: undefined,
//   originalId: 0
// }

outputTravel(travelId, 0); // Original
// Travel 0 (revision 0):
//  {
//   title: 'Nacht der Wissenschaft',
//   location: 'Hof',
//   start: 1925-11-14T09:30:00.000Z,
//   end: 1925-11-14T17:30:00.000Z,
//   amount: 120.99,
//   details: 'Bahnticket 100, ÖPNV 20,99'
// }

outputTravel(travelId, 1); // Revision 1
// Travel 0 (revision 1):
//  {
//   title: 'Nacht der Wissenschaft',
//   location: 'Hochschule Hof',
//   start: 1925-11-14T09:30:00.000Z,
//   end: 1925-11-14T17:30:00.000Z,
//   amount: 120.99,
//   details: 'Bahnticket 100, ÖPNV 20,99'
// }

outputTravel(travelId, 2); // Revision 2
// Travel 0 (revision 2):
//  {
//   title: 'Nacht der Wissenschaft',
//   location: 'Hochschule Hof',
//   start: 1925-11-14T09:30:00.000Z,
//   end: 1925-11-14T17:30:00.000Z,
//   amount: 100,
//   details: 'Bahnticket 100, ÖPNV 20,99'
// }

outputTravel(travelId, 3); // Revision 3
// Travel 0 (revision 3):
//  {
//   title: undefined,
//   location: undefined,
//   start: undefined,
//   end: undefined,
//   amount: undefined,
//   details: undefined
// }

outputTravelRevisions(travelId); // Alle States ausgeben
// Travel 0 - States between revision initial and current: [
//     Travel {
//       title: 'Nacht der Wissenschaft',
//       location: 'Hof',
//       start: 1925-11-14T09:30:00.000Z,
//       end: 1925-11-14T17:30:00.000Z,
//       amount: 120.99,
//       details: 'Bahnticket 100, ÖPNV 20,99'
//     },
//     {
//       title: 'Nacht der Wissenschaft',
//       location: 'Hochschule Hof',
//       start: 1925-11-14T09:30:00.000Z,
//       end: 1925-11-14T17:30:00.000Z,
//       amount: 120.99,
//       details: 'Bahnticket 100, ÖPNV 20,99',
//       originalId: 0
//     },
//     {
//       title: 'Nacht der Wissenschaft',
//       location: 'Hochschule Hof',
//       start: 1925-11-14T09:30:00.000Z,
//       end: 1925-11-14T17:30:00.000Z,
//       amount: 100,
//       details: 'Bahnticket 100, ÖPNV inklusive',
//       originalId: 0
//     },
//     {
//       title: undefined,
//       location: undefined,
//       start: undefined,
//       end: undefined,
//       amount: undefined,
//       details: undefined,
//       originalId: 0
//     }
//   ]

outputTravelRevisions(travelId, 0, 1); // States von State 0 auf State 1
// Travel 0 - States between revision 0 and 1: [
//     Travel {
//       title: 'Nacht der Wissenschaft',
//       location: 'Hof',
//       start: 1925-11-14T09:30:00.000Z,
//       end: 1925-11-14T17:30:00.000Z,
//       amount: 120.99,
//       details: 'Bahnticket 100, ÖPNV 20,99'
//     }
//   ]

// Änderungen von State 0 auf State 1
outputTravelRevisions(travelId, 0, 2);
// Travel 0 - States between revision 0 and 2: [
//     Travel {
//       title: 'Nacht der Wissenschaft',
//       location: 'Hof',
//       start: 1925-11-14T09:30:00.000Z,
//       end: 1925-11-14T17:30:00.000Z,
//       amount: 120.99,
//       details: 'Bahnticket 100, ÖPNV 20,99'
//     },
//     {
//       title: 'Nacht der Wissenschaft',
//       location: 'Hochschule Hof',
//       start: 1925-11-14T09:30:00.000Z,
//       end: 1925-11-14T17:30:00.000Z,
//       amount: 120.99,
//       details: 'Bahnticket 100, ÖPNV 20,99',
//       originalId: 0
//     }
//   ]

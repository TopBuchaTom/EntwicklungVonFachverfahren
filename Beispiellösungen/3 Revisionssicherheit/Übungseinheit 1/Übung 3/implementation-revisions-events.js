// Event Sourcing:
// - minimaler Speicherverbrauch, da nur Änderungen gespeichert werden, statt Kopien aller Daten
// - einzelne Änderungen können User auch übersichtlich dargestellt werden und je nach Umsetzung könnte Rollback implementiert werden
// - Berechnung von aktuellem Zustand erfordert Zeit
// - Implementierung gestaltet sich komplexer

class Event {
    constructor(entityType, recordId, changes = null) {
        this.entityType = entityType;
        this.recordId = recordId;
        this.changes = changes;
    }
}

class EventRepository {
    #events = [];

    getWithAppliedEvents(entityType, recordId, instance, eventId = undefined) {
        const events = this.getEvents(entityType, recordId, 0, eventId);

        return this.#applyEvents(instance, events);
    }

    getEvents(entityType, recordId, minEventId = undefined, maxEventId = undefined) {
        return this.#events.slice(minEventId, maxEventId)
            .filter(event => event.entityType == entityType && event.recordId == recordId);
    }

    createUpdateEvent(entityType, recordId, previous, current) {
        const changes = this.#calculateChanges(previous, current);

        if (Object.keys(changes).length > 0)
            return this.#events.push(new Event(entityType, recordId, changes)) > 0;

        return false;
    }

    #calculateChanges(previous, current) {
        const changes = {};
        for (const prop in previous)
            if (current[prop] != previous[prop])
                changes[prop] = current[prop];

        return changes;
    }

    // #calculateChanges(previous, current) {
    //     return Object.keys(current).reduce((changes, prop) => current[prop] != previous[prop] ? { ...changes, [prop]: current[prop] } : changes, {});
    // }

    #applyEvents(entity, events) {
        const result = {...entity};

        for (const event of events)
            Object.assign(result, event.changes);

        return result;
    }

    // #applyEvents(entity, events) {
    //     return events.reduce((result, event) => Object.assign(result, event.changes), entity);
    // }

    static #sharedInstance;

    static get sharedInstance() {
        return EventRepository.#sharedInstance ??= new EventRepository();
    }
}

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
    #eventRepository = EventRepository.sharedInstance;

    getTravel(id, revisionId = undefined) {
        const original = this.#travels[id];

        return this.#eventRepository.getWithAppliedEvents(Travel.name, id, original, revisionId);
    }

    getRevisions(id, minRevisionId, maxRevisionId) {
        return this.#eventRepository.getEvents(Travel.name, id, minRevisionId, maxRevisionId);
    }

    insertTravel(travel) {
        return this.#travels.push(travel) - 1;
    }

    updateTravel(id, travel) {
        const previous = this.getTravel(id);

        return this.#eventRepository.createUpdateEvent(Travel.name, id, previous, travel);
    }

    deleteTravel(id) {
        const previous = this.getTravel(id);

        return this.#eventRepository.createUpdateEvent(Travel.name, id, previous, {});
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
//  {
//   title: undefined,
//   location: undefined,
//   start: undefined,
//   end: undefined,
//   amount: undefined,
//   details: undefined
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

outputTravelRevisions(travelId); // Alle Events ausgeben
// Travel 0 - Events between revision initial and current: [
//     Event {
//       entityType: 'Travel',
//       recordId: 0,
//       changes: { location: 'Hochschule Hof' }
//     },
//     Event {
//       entityType: 'Travel',
//       recordId: 0,
//       changes: { amount: 100, details: 'Bahnticket 100, ÖPNV inklusive' }
//     },
//     Event {
//       entityType: 'Travel',
//       recordId: 0,
//       changes: {
//         title: undefined,
//         location: undefined,
//         start: undefined,
//         end: undefined,
//         amount: undefined,
//         details: undefined
//       }
//     }
//   ]

outputTravelRevisions(travelId, 0, 1); // Events von State 0 auf State 1
// Travel 0 - Events between revision 0 and 1: [
//     Event {
//       entityType: 'Travel',
//       recordId: 0,
//       changes: { location: 'Hochschule Hof' }
//     }
//   ]

outputTravelRevisions(travelId, 0, 2); // Events von State 0 auf State 2
// Travel 0 - Events between revision 0 and 2: [
//     Event {
//       entityType: 'Travel',
//       recordId: 0,
//       changes: { location: 'Hochschule Hof' }
//     },
//     Event {
//       entityType: 'Travel',
//       recordId: 0,
//       changes: { amount: 100, details: 'Bahnticket 100, ÖPNV inklusive' }
//     }
//   ]

var category = {
Sports: {color: "00FF21"},
Arts: {color: "FF0000"},
Media: {color: "0094FF"} };


var interests = [
{name: "Football", img: "football.png", category: "Sports"},
{name: "Basketball", img: "basketball.png", category: "Sports"},
{name: "Dance", img: "dance.png", category: "Arts"},
{name: "Photography", img: "camera.png", category: "Media"},
{name: "Movies", img: "film.png", category: "Media"}
]


var events = [
{lat: 59.92265475733866, lng: 10.803101062774658, interest: "Football", title: "Practice", time: 141647040043200},
{lat: 59.925257101959204, lng: 10.778124332427979, interest: "Dance", title: "Show", time: 1416502800},
{lat: 59.92170839960051, lng: 10.800440311431885, interest: "Basketball", title: "G15 match", time: 1416477600},
{lat: 59.92065446944626, lng: 10.788252353668213, interest: "Photography", title: "Beginner class", time: 1416499200},
{lat: 59.9157930522404, lng: 10.772287845611572, interest: "Movies", title: "Making music video", time: 1416488400},
{lat: 59.91329753499344, lng: 10.79331636428833, interest: "Football", title: "Street match", time: 1416513600},
{lat: 59.920805032946184, lng: 10.764734745025635, interest: "Football", title: "Indoor practice", time: 1416510000},
{lat: 59.92736463644395, lng: 10.78533411026001, interest: "Dance", title: "Breakdance", time: 1416513600}
];


function getInterest(name) {
    for (var i in interests) {
        if(interests[i].name == name) {
            return interests[i];
        }
    }

    return null;
}

function getEventsWithInterest(inter) {
    var relevantEvents = [];
    for(var i in events) {
        if(events[i].interest == inter) {
            relevantEvents.push(events[i]);
        }
    }

    return relevantEvents;
}

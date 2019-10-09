// weather from OpenWeather API (https://openweathermap.org/current)
// hosted by Azure Functions

const axios = require('axios');
const WEATHER_API_URL = 'https://api.openweathermap.org/data/2.5';

module.exports = async function (context, req) {
    const location = req.body.conversation.memory.location;
    const language = req.body.nlp.language;
    let replyText = "";
    let errorText = (language == 'fr') ? "Je suis désolé, je n'ai pas pu trouver votre emplacement. Veuillez réessayer avec une région plus large." : "I'm sorry, I couldn't find your location. Please try again with a broader region.";

    return axios
        .get(`${WEATHER_API_URL}/weather`, {
            params: {
                lat: Math.round(location.lat),
                lon: Math.round(location.lng),
                units: 'metric',
                lang: language,
                appid: '415efa24ed1e560c5e428f39df38c38c', // *** REQUIRED: API key ***
            },
        })
        .then(function (response) {
            const body = response.data;
            if (!body || !body.weather || body.weather.length === 0) {
                return res.json({
                    replies: [{
                        type: 'text',
                        content: errorText,
                    }, ],
                });
            }

            let mainWeather = body.weather[0].main;
            if (mainWeather === 'Clear') {
                mainWeather = 'Sun';
            }
            let mainDesc = body.weather[0].description;

            // reset location memory
            delete req.body.conversation.memory.location;

            if (language == 'fr') {
                replyText = `La prévision pour ${location.formatted} aujourd'hui est **${mainDesc.toLowerCase()}**, avec une température ${weatherAdjFR(body.main.temp)} de **${Math.round(body.main.temp)} °C**! (${Math.round(body.main.temp_max)}/${Math.round(body.main.temp_min)}) ${weatherEmoji(mainWeather)}`;
            } else {
                replyText = `The forecast for ${location.formatted} today is **${mainDesc.toLowerCase()}**, with a ${weatherAdjEN(body.main.temp)} temperature of **${Math.round(body.main.temp)}°C**! (${Math.round(body.main.temp_max)}/${Math.round(body.main.temp_min)}) ${weatherEmoji(mainWeather)}`;
            }

            return context.res.json({
                replies: [{
                    type: 'text',
                    content: replyText,
                    markdown: true,
                }, ],
                conversation: {
                    memory: req.body.conversation.memory
                },
            });
        })
}

function weatherAdjEN(temp) {
    if (temp < -40) {
        return 'frigid';
    } else if (temp < -30) {
        return 'frosty';
    } else if (temp < -20) {
        return 'chilly'
    } else if (temp < -10) {
        return 'cool';
    } else if (temp < 0) {
        return 'crisp';
    } else if (temp < 10) {
        return 'brisk';
    } else if (temp < 20) {
        return 'refreshing';
    } else if (temp < 30) {
        return 'warm';
    } else if (temp < 40) {
        return 'hot';
    } else {
        return 'boiling';
    }
}

function weatherAdjFR(temp) {
    if (temp < -40) {
        return 'glaciale';
    } else if (temp < -20) {
        return 'froide';
    } else if (temp < 0) {
        return 'fraîche';
    } else if (temp < 20) {
        return 'rafraîchissante';
    } else if (temp < 40) {
        return 'chaude';
    } else {
        return 'canicule';
    }
}

// OpenWeather API weather status (https://openweathermap.org/weather-conditions)
function weatherEmoji(weather) {
    switch (weather.toLowerCase()) {
        case 'sun':
            return '☀️';
        case 'clouds':
            return '☁️';
        case 'mist':
        case 'smoke':
        case 'haze':
        case 'dust':
        case 'fog':
        case 'dust':
        case 'ash':
            return '🌫️';
        case 'sand':
            return '🏜️';
        case 'squall':
            return '🌬️';
        case 'tornado':
            return '🌪️';
        case 'thunderstorm':
            return '⚡️';
        case 'drizzle':
            return '🌧️';
        case 'rain':
            return '☔️';
        case 'snow':
            return '❄️';
        default:
            return '🌡️';
    }
}


// *** original code for Heroku ***

// const express = require('express'); ^4.17.1
// const app = express();
// const bodyParser = require('body-parser'); ^1.19.0

// app.use(bodyParser.json());
// loadWeather(app);

// app.post('/errors', function (req, res) {
//     console.log(req.body);
//     res.sendStatus(200);
// });

// const port = process.env.PORT || 5000;
// app.listen(port, function () {
//     console.log(`App is listening on port ${port}`);
// });

// function loadWeather(app) {
//     app.post('/', function (req, res) {
//         return res.json({});
//     });
// }

// module.exports = loadWeather;
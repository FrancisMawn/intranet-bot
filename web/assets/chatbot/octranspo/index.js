// bus schedule from OC Transpo API (http://www.octranspo.com/en/plan-your-trip/travel-tools/developers/dev-doc)
// hosted by Azure Functions

const axios = require('axios');
const BUS_API_URL = 'https://api.octranspo1.com/v1.3';

module.exports = async function (context, req) {
  const bus_stop = req.body.conversation.memory.bus_stop.raw;
  const language = req.body.nlp.language;
  let replyText = "";

  return axios
    .get(`${BUS_API_URL}/GetNextTripsForStopAllRoutes`, {
      params: {
        appID: 'd80f6694', // *** REQUIRED: app ID ***
        apiKey: 'f8c2fae7dd8ce986dd1321e4e664d3d4', // *** REQUIRED: API key ***
        stopNo: bus_stop,
        format: 'json'
      },
    })
    .then(function (response) {
      const body = response.data;
      switch (true) {
        case (!body || !body.GetRouteSummaryForStopResult.StopDescription):
        case (body.GetRouteSummaryForStopResult.StopDescription == '' && body.GetRouteSummaryForStopResult.Error != ''):
          replyText += language == 'fr' ? "Je suis désolé, je n'ai pas pu trouver votre arrêt d'autobus. Veuillez réessayer avec un numéro d'arrêt différent." : "I'm sorry, I couldn't find your bus stop. Please try again with a different stop number.";
          break;

        case (body.GetRouteSummaryForStopResult.StopDescription != '' && body.GetRouteSummaryForStopResult.Routes.Route == null):
        case (body.GetRouteSummaryForStopResult.StopDescription != '' && Array.isArray(body.GetRouteSummaryForStopResult.Routes.Route.Trips) && !body.GetRouteSummaryForStopResult.Routes.Route.Trips.length):
          replyText += body.GetRouteSummaryForStopResult.StopDescription + '\n\n';
          replyText += language == 'fr' ? "Il n'y a pas d'autobus desservant cet arrêt pour le moment." : "There are no busses servicing this stop at this time.";
          break;

        default:
          replyText += body.GetRouteSummaryForStopResult.StopDescription;
          let stopRoutes = body.GetRouteSummaryForStopResult.Routes.Route;

          if (Array.isArray(stopRoutes)) {
            for (i in stopRoutes) {
              if ((Array.isArray(stopRoutes[i].Trips) && stopRoutes[i].Trips.length) || stopRoutes[i].Trips.TripStartTime) {
                replyText += '\n\nRoute ' + stopRoutes[i].RouteNo;
                replyText += language == 'fr' ? ' à ' : ' to ';
                replyText += stopRoutes[i].RouteHeading + '\n';
              }

              if (Array.isArray(stopRoutes[i].Trips) && stopRoutes[i].Trips.length) {
                for (j in stopRoutes[i].Trips) {
                  replyText += stopRoutes[i].Trips[j].TripStartTime + ' ';
                }
              } else if (stopRoutes[i].Trips.TripStartTime) {
                replyText += stopRoutes[i].Trips.TripStartTime;
              }
            }
          } else {
            if ((Array.isArray(stopRoutes.Trips.Trip) && stopRoutes.Trips.Trip.length) || stopRoutes.Trips.Trip.TripStartTime) {
              replyText += '\n\nRoute ' + stopRoutes.RouteNo;
              replyText += language == 'fr' ? ' à ' : ' to ';
              replyText += stopRoutes.RouteHeading + '\n';
            }

            if (Array.isArray(stopRoutes.Trips.Trip) && stopRoutes.Trips.Trip.length) {
              for (j in stopRoutes.Trips.Trip) {
                replyText += stopRoutes.Trips.Trip[j].TripStartTime + ' ';
              }
            } else if (stopRoutes.Trips.Trip.TripStartTime) {
              replyText += stopRoutes.Trips.Trip.TripStartTime;
            }
          }

          if (replyText.length == body.GetRouteSummaryForStopResult.StopDescription.length) {
            replyText += '\n\n';
            replyText += language == 'fr' ? "Il n'y a pas d'autobus desservant cet arrêt pour le moment." : "There are no busses servicing this stop at this time.";
          } else {
            replyText += '\n\n';
            replyText += language == 'fr' ? "Veuillez noter que les autobus qui ne sont pas actuellement en service ont été omis." : "Please note that busses not currently in service have been omitted.";
          }
          break;
      }

      // reset bus_stop memory
      delete req.body.conversation.memory.bus_stop;

      return context.res.json({
        replies: [{
          type: 'text',
          content: replyText,
        }, ],
        conversation: {
          memory: req.body.conversation.memory
        },
      });
    })
}
function calendarThis(json) {
    $("#employeeCalendar").fullCalendar({
        navLink: true,
        header: {
            left: "month, basicWeek, basicDay"
        },
        events: json,
        eventClick: function (calEvent, jsEvent, view) {
            if (calEvent.done == false && confirm("Êtes-vous sûr de vouloir valider cette tâche ? (Action irreversible)")) {
                $.ajax({
                    url: "/employee/done/" + calEvent.id,
                    dataType: "json",
                    type: "POST",
                    data: {
                        "valid": true
                    },
                    success: function (json) {
                        if (json.error === undefined) {
                            alert("La tâche a été marquée à \"Terminé\"");
                            // location.href = "#"
                            calEvent.done = true;
                            $('#employeeCalendar').fullCalendar('updateEvent', calEvent);
                        } else {
                            alert(json.error);
                        }
                    },
                    error: function () {
                        alert("Impossible de valider la tâche.");
                    }

                })
            }
            console.log("Event:", calEvent, "JSEvent:", jsEvent, "View:", view)
        }
    })
}

$(function () {
    var id = $("#employeeCalendar").attr("data-employeeId");
    $.ajax({
        url: "/employee/"+id+"/tasks",
        dataType: "json",
        type: "GET",
        success: function (json) {
            if (json.error === undefined) {
                calendarThis(json);
            } else {
                alert(json.error);
            }
        },
        error: function () {
            alert("Impossible de charger la page.");
        }

    })
})
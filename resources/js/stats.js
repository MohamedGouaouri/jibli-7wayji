$(document).ready(() => {
    $.ajax({
        type: "GET",
        url: "admin_analytics_api"
    }).done(server_data => {
        let nb_transporters = server_data["users"]["nb_transporters"];
        console.log(nb_transporters);
        const user_types_data = {
            labels: ['Users', 'Transporters'],
            datasets: [{
                label: '# Users',
                data: [server_data["users"]["nb_users"], nb_transporters, 0],
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                ],
                borderWidth: 1
            }]
        };
        const ctx1 = document.getElementById('myChart1').getContext('2d');
        new Chart(ctx1, {
            type: 'bar',
            data: user_types_data,
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        const announcements_data = {
            labels: ['Validated ads', 'Non validated ads'],
            datasets: [{
                label: 'announcements',
                data: [server_data["announcements"]["nb_validated"], server_data["announcements"]["nb_non_validated"]],
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                ],
                borderWidth: 1
            }]
        };

        const ctx2 = document.getElementById('myChart2').getContext('2d');
        new Chart(ctx2, {
            type: "doughnut",
            data: announcements_data
        });
    })
})
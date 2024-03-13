<div
    x-data="{
        posts: {},
        getPublicIP() {
            fetch('https://api.ipify.org?format=json')
                .then(response => response.json())
                .then(data => {
                    this.posts = data.ip;
                    $wire.ox=data.ip;
                })
                .catch(error => console.error('Erreur lors de la récupération de l\'adresse IP :', error));
        }
    }"
    x-init="getPublicIP;">
</div>
<template>
    <div class="container">
        <div id="app">
            <div>
                <h2>Seleccionar una ciudad:</h2>
                <select v-model="selectedCity" @change="getHumidity">
                    <option value="">Seleccionar ciudad</option>
                    <option v-for="city in cities" :value="city.id" :key="city.id">@{{ city.name }}</option>
                </select>
            </div>
            <div v-if="humidity !== null">
                <h3>Humedad actual en {{ selectedCityName }}: {{ humidity }}%</h3>
            </div>
            <div id="map"></div>
        </div>
    </div>
</template>
<script>
export default {
    name: "FormComponent",
    data() {
        return {
            selectedCity: '',
            cities: [],
            humidity: null,
            selectedCityName: '',
            map: null
        }
    },
    mounted() {
        axios.get('/api/cities')
            .then(response => {
                this.cities = response.data;
            })
            .catch(error => {
                console.error(error);
            });
        this.map = L.map('map').setView([25.7617, -80.1918], 10);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(this.map);
    },
    methods: {
        getHumidity() {
            if (this.selectedCity) {
                axios.post('/api/cities/humidity', {
                    city_id: this.selectedCity
                })
                    .then(response => {
                        this.humidity = response.data.humidity;
                        this.selectedCityName = this.cities.find(city => city.id === this.selectedCity).name;
                    })
                    .catch(error => {
                        console.error(error);
                    });
            } else {
                this.humidity = null;
                this.selectedCityName = '';
            }
        }
    }
}
</script>

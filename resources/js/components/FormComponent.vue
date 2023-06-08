<template>
    <div class="container">
        <div id="app">
            <div>
                <h2>Seleccionar una ciudad:</h2>
                <select v-model="selectedCity" @change="getHumidity">
                    <option value="">Seleccionar ciudad</option>
                    <option v-for="city in cities" :value="city.id" :key="city.id">{{ city.name }}</option>
                </select>
            </div>
            <div v-if="humidity !== null">
                <h3>Humedad actual en {{ selectedCityName }}: {{ humidity }}%</h3>
            </div>
            <div id="map" style="width: 100%; height: 400px;"></div>
        </div>
    </div>
</template>

<script>
import L from 'leaflet';
import 'leaflet/dist/leaflet.css';

export default {
    name: "FormComponent",
    data() {
        return {
            selectedCity: "",
            cities: [],
            humidity: null,
            selectedCityName: "",
            map: null,
            defaultCoords: [25.7617, -80.1918],
        };
    },
    mounted() {
        axios
            .get("/api/cities")
            .then((response) => {
                this.cities = response.data;
            })
            .catch((error) => {
                console.error(error);
            });
    },
    methods: {
        getHumidity() {
            if (this.selectedCity) {
                const selectedCity = this.cities.find((city) => city.id === this.selectedCity);
                this.updateMapCenter(selectedCity.lat, selectedCity.lon);
                if (selectedCity) {
                    axios
                        .post("/api/cities/humidity", {
                            cityId: this.selectedCity,
                        })
                        .then((response) => {
                            this.humidity = response.data.humidity;
                            this.selectedCityName = selectedCity.name;
                        })
                        .catch((error) => {
                            console.error(error);
                        });
                }
            } else {
                this.humidity = null;
                this.selectedCityName = "";
                this.updateMapCenter();
            }
        },
        initializeMap() {
            this.map = L.map("map").setView(this.defaultCoords, 10);
            L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
                attribution: "&copy; OpenStreetMap contributors",
            }).addTo(this.map);
        },
        updateMapCenter(lat, lon) {
            this.map.setView([lat, lon], 12);
        },
    },
    created() {
        this.$nextTick(() => {
            this.initializeMap();
        });
    },
};
</script>

<style>
#map {
    width: 100%;
    height: 400px;
}
</style>

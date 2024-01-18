<template>
    <div class="d-flex align-center flex-column">
        <div class="brown-text title">Tabacos</div>
        <v-card class="elevation-12" width="400">
            <v-card-text>
                <v-row>
                    <v-col cols="12">
                        <div class="info-row">
                            <span class="info-label">Producción Total de Tabacos:</span>
                            <span class="info-value">53,350</span>
                        </div>
                    </v-col>
                    <v-col cols="12">
                        <div class="info-row">
                            <span class="info-label">Cantidad producida por la B1:</span>
                            <span class="info-value">38,200</span>
                        </div>
                    </v-col>
                    <v-col cols="12">
                        <div class="info-row">
                            <span class="info-label">Cantidad producida por la B2:</span>
                            <span class="info-value">15,150</span>
                        </div>
                    </v-col>
                </v-row>
            </v-card-text>
        </v-card>

        <div class="mt-4 brown-text title">Brigadas</div>
        <v-card class="elevation-12" width="400">
            <v-card-text>
                <v-row>
                    <v-col cols="12">
                        <div class="info-row">
                            <span class="info-label">Cantidad de brigadas:</span>
                            <span class="info-value">2</span>
                        </div>
                    </v-col>
                    <v-col cols="12">
                        <div class="info-row">
                            <span class="info-label">Cantidad de trabajadores:</span>
                            <span class="info-value">62</span>
                        </div>
                    </v-col>
                </v-row>
            </v-card-text>
        </v-card>
        
        <div class="mt-4 brown-text title" >Planificación</div>
        <v-card class="elevation-12" width="400">
            <v-card-text>
                <v-row>
                    <v-col cols="12">
                        <div class="info-row">
                            <span class="info-label">Planificación del mes:</span>
                            <span class="info-value">15,000</span>
                        </div>
                    </v-col>
                    <v-col cols="12">
                        <div class="info-row">
                            <span class="info-label">Planificación diaria:</span>
                            <span class="info-value">535</span>
                        </div>
                    </v-col>
                    <v-col cols="12">
                        <div class="info-row">
                            <span class="info-label">Planificación cumplida:</span>
                            <span class="info-value">27%</span>
                        </div>
                    </v-col>
                </v-row>
            </v-card-text>
        </v-card>
    </div>
</template>
  

<script>

import NavBar from '@/components/NavBar.vue'; 
export default{
    components:{
        NavBar,
    }
}

</script>



<style scoped>
.brown-text {
    color: brown;
    font-weight: bold;
    font-size: 18px;
    /* Puedes ajustar el tamaño según tus necesidades */
}

.title {
    font-size: 24px;
    /* Puedes ajustar el tamaño según tus necesidades */
}

.info-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 8px;
}

.info-label {
    color: rgba(165, 42, 42, 0.829);
    font-style: italic;
}

.info-value {
    font-weight: bold;
}
</style>
  
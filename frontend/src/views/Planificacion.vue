<template>
<v-container>
  
  <v-row row wrap>

    <v-col md6 >
       <v-card class="mb-3">
       <v-card-text>
        <h2 style="color:black;">Crear Planificacion Mensual</h2>
       </v-card-text>
       <div>
        <v-divider
        :thickness="8"
        class="border-opacity-50"
          color="brown"
>       </v-divider>
       </div>
       <br>
        <v-form @submit.prevent="agregarPlanificacion">
            
        <v-text-field type="number" label="Introduzca el año" variant="outlined" v-model="anno" ></v-text-field>
        <br>
        <v-text-field  label="Introduzca el mes" variant="outlined" v-model="mes" ></v-text-field>
       <br>
       <v-text-field type="number" label="Introduzca los dias laborales" variant="outlined" v-model="diasLaborales"></v-text-field>
       <br>
       <v-text-field  type="number" label="Introduzca la planificacion del mes" variant="outlined" v-model="planificacion"></v-text-field>


       <v-btn black color="brown" class="ml-15" type="submit">Crear Produccion</v-btn>
        </v-form>
       </v-card>
       </v-col>

       



 <v-col md6 >

<v-card class="mb-3" v-for="(item,index) in listaPlanificaciones" :key="index">
<v-card-text>
  <h2>Planificacion Creada</h2>
</v-card-text> 

<div>
    <v-divider
    :thickness="8"
    class="border-opacity-50"
      color="brown"
>       </v-divider>
   </div>
  
  <v-card-text>
    <v-chip
  class="ma-2"
  color="brown"
  label
>
   <v-icon start icon="mdi-label"></v-icon>
  {{ item.anno }}
  </v-chip>
 <ul>
    <li>
       {{ item.mes}}
    </li>
    <li>
        {{ item.diasLaborales }}
    </li>
    <li>
        {{ item.planificacion}}
    </li>
 </ul>
 <br>
  
  </v-card-text> 

</v-card>

</v-col>

  </v-row>
  <v-snackbar
        v-model="snackbar"
      >
        {{ mensaje }}
  
        <template v-slot:actions>
          <v-btn
            color="pink"
            variant="text"
            @click="snackbar = false"
          >
            Cerrar
          </v-btn>
        </template>
      </v-snackbar>
</v-container>

</template>

<script>

export default{

data(){
    return{
    listaPlanificaciones:[
     {id:1, anno:'anno',mes:'mes',diasLaborales:'dias laborales',planificacion:'planificacion mensual'}, 
     {id:2, anno:'anno 2',mes:'mes',diasLaborales:'dias laborales',planificacion:'planificacion mensual'}
    ],

    anno:'',   
    mes:'',
    diasLaborales:'',
    planificacion:'',
    snackbar:false,
    mensaje: '',
    indexProduccion:''
    }
  },
  methods:{ 
   agregarPlanificacion(){
    if(this.anno ==='' || this.mes ===''  || this.diasLaborales ==='' || this.planificacion === ''){
      this.snackbar = true
      this.mensaje = 'Llena todos los campos!'
    }else{
      this.listaPlanificaciones.push({
        id:Date.now(),
        anno : this.anno,
        mes: this.mes,
        diasLaborales: this.diasLaborales,
        planificacion: this.planificacion,
      })
     this.anno = '',
     this.mes = '',
     this.diasLaborales = '',
     this.planificacion=''
     this.snackbar = true
     this.mensaje = 'Produccion creada con exito!'
    }
   },  
}
}


</script>
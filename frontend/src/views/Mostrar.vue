<template>
  <v-container style="margin-top: 60px;">
    <div>
      <v-divider
      :thickness="8"
      class="border-opacity-50"
        color="green"
        style="margin-top:5px;"
    >       </v-divider>
     </div>
  <v-text-field label="Seleccione la fecha " type="date" 
    @input="selected"></v-text-field>
  </v-container>
<v-container>

<v-row row wrap style="margin-top: -40px;">
<v-col md6>
  <v-card-text style="margin-top: -20px;">
    <v-chip color="success">
    <h1>Lista de Producciones del Día Seleccionado:</h1>
   </v-chip>
   </v-card-text>
   

   <v-card class="mb-3" >
    <v-virtual-scroll
    :items="Array.from({length: 100}).map((_, index) => index)"
    :item-height="50"
    height="548"
    style="margin-top: 20px;"
    
    >
   <v-card  class="mb-2" v-for="(item,index) in listaProducciones" :key="index">
   <v-card-text>
    <v-chip
  class="ma-2"
  color="green"
  
  
>
   <v-icon start icon="mdi-label"></v-icon>
 {{ item.Categoria }}
  </v-chip>
 <ul class="ml-3">
    <li>
      {{ item.vitola }}
    </li>
    <li>
       {{ item.brigada }}
    </li>
    <li>
      {{ item.cant_trabajadores }}
    </li>
    <li>
      {{ item.cant_producida }}
    </li>
 </ul>
 <br>
 <div>
  <v-divider
  :thickness="8"
  class="border-opacity-50"
    color="green"
    style="margin-top:5px;"
>       </v-divider>
 </div>
  </v-card-text> 
  
</v-card>
</v-virtual-scroll>

</v-card>

</v-col>


</v-row>


</v-container>
</template>


<script>
export default {
  data(){
    return{
    listaProducciones:[
     { Categoria:'Categoria#1',vitola:'vitola',brigada:'brigada',cant_trabajadores:'cant trabajadores',cant_producida:'cant producida'}
    ],

    Categoria:'',   
    vitola:'',
    brigada:'',
    cant_trabajadores:'',
    cant_producida:'',
    }
},  

  methods: {
    selected() {

    }
  },

}
</script>
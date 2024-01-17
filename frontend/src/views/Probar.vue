<template>
  <v-card class="flex-grow-0">
    <v-virtual-scroll
    :items="Array.from({length: 100}).map((_, index) => index)"
    :item-height="50"
    height="300"
    ><v-card class="mb-3" v-for="(item,index) in listaProducciones" :key="index">
      <v-card-text>
        
        <h1>Producción Creada</h1>
       
      </v-card-text> 
      
      <div>
          <v-divider
          :thickness="8"
          class="border-opacity-50"
            color="success"
  >       </v-divider>
         </div>
        
        <v-card-text>
          <v-chip
        class="ma-2"
        color="green"
        label
  >
         <v-icon start icon="mdi-label"></v-icon>
        {{ item.Categoria }}
        </v-chip>
       <ul>
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
        <v-btn color="green">Editar</v-btn>
        <v-btn color="error" class="ml-10" >Eliminar</v-btn>
        </v-card-text> 
      
    
      </v-card>
      
    </v-virtual-scroll>
  </v-card>
</template>
<script>
export default{
data(){
    return{
    listaProducciones:[
     { Categoria:'Categoria#1',vitola:'vitola',brigada:'brigada',cant_trabajadores:'cant trabajadores',cant_producida:'cant producida'}, 
     { Categoria:'Categoria#2',vitola:'vitola',brigada:'brigada',cant_trabajadores:'cant trabajadores',cant_producida:'cant producida'}
    ],

    Categoria:'',   
    vitola:'',
    brigada:'',
    cant_trabajadores:'',
    cant_producida:'',
    snackbar:false,
    mensaje: '',
    formAgregar: true,
   
   
    
    }
 }

}
</script>
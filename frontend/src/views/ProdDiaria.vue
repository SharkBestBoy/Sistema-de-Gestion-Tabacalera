<template>
  <v-container grid-list-xL>
    <v-row row wrap>


      <!--Aqui esta el formulario principal encargado de crear las producciones-->

      <v-col md6 v-if="formAgregar">
        <v-card class="mb-3">
          <v-card-text>
            <h1 style="color:black;">Crear Produccion Diaria</h1>
          </v-card-text>
          <div>
            <v-divider :thickness="8" class="border-opacity-50" color="success"> </v-divider>
          </div>
          <br>
          <v-form @submit.prevent="agregarProduccion">
            <v-autocomplete label=" Escriba la categoria" :items="['IX', 'VIII', 'VII']" v-model="Categoria"
              @blur="autoCompletableNombreVitola"></v-autocomplete>
            <br>
            <v-autocomplete label="Escriba el nombre de la vitola" :items="arrayNombreVitola"
              v-model="vitola"></v-autocomplete>
            <br>
            <v-autocomplete label=" Escriba el # de la brigada" :items="arrayBrigada" v-model="brigada"
              @blur="autoCompletableBrigadas"></v-autocomplete>
            <br>
            <v-text-field label=" Cantidad Trabajores de la Brigada" variant="outlined"
              v-model="cant_trabajadores"></v-text-field>
            <br>
            <v-text-field label="Introduzca Cantidad Producida" variant="outlined"
              v-model="cant_producida"></v-text-field>
            <br>
            <v-btn black color="success" class="ml-15" type="submit">Crear Produccion</v-btn>
          </v-form>
        </v-card>
      </v-col>



      <!--Aqui esta el formulario copiado para la operacion del editar-->
      <v-col md6 v-if="!formAgregar">
        <v-card class="mb-3">
          <v-card-text>
            <h1 style="color:black;">Crear Produccion Diaria</h1>
          </v-card-text>
          <div>
            <v-divider :thickness="8" class="border-opacity-50" color="success"> </v-divider>
          </div>
          <br>
          <v-form @submit.prevent="editarProduccionA">
            <v-autocomplete label=" Escriba la categoria" :items="['IX', 'VIII', 'VII']" v-model="Categoria"
              @blur="autoCompletableNombreVitola"></v-autocomplete>
            <br>
            <v-autocomplete label="Escriba el nombre de la  vitola" :items="arrayNombreVitola"
              v-model="vitola"></v-autocomplete>
            <br>
            <v-autocomplete label=" Escriba la brigada" :items="arrayBrigada" v-model="brigada"
              @blur="autoCompletableBrigadas"></v-autocomplete>
            <br>
            <v-text-field label=" Cantidad Trabajores de la Brigada" variant="outlined"
              v-model="cant_trabajadores"></v-text-field>
            <br>
            <v-text-field label="Introduzca Cantidad Producida" variant="outlined"
              v-model="cant_producida"></v-text-field>
            <br>
            <v-btn black color="warning" class="ml-15" type="submit">Editar Produccion</v-btn>
          </v-form>
        </v-card>
      </v-col>





      <!--Aqui se encuentra todo lo relacionado con las producciones que se crean-->
      <v-col md6>

        <v-card class="mb-3" v-for="(item, index) in listaProducciones" :key="index">
          <v-card-text>
            <h1>Produccion Creada</h1>
          </v-card-text>

          <div>
            <v-divider :thickness="8" class="border-opacity-50" color="success"> </v-divider>
          </div>

          <v-card-text>
            <v-chip class="ma-2" color="green" label>
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
            <v-btn color="green" @click="editarProduccion(index)">Editar</v-btn>
            <v-btn color="error" @click="eliminarProduccion(item.id)" class="ml-10">Eliminar</v-btn>
          </v-card-text>

        </v-card>

      </v-col>


    </v-row>
    <!--Snackbar que muestra los mensajes cuando se realizan las operaciones-->
    <v-snackbar v-model="snackbar">
      {{ mensaje }}

      <template v-slot:actions>
        <v-btn color="pink" variant="text" @click="snackbar = false">
          Cerrar
        </v-btn>
      </template>
    </v-snackbar>


  </v-container>
</template>
<!--Aqui estan los scripts(en la seccion data estan los datos y en methods estan todos los metodos)-->
<script>
import axios from 'axios';

export default {

  data() {
    return {
      listaProducciones: [
        { id: 1, Categoria: 'Categoria#1', vitola: 'vitola', brigada: 'brigada', cant_trabajadores: 'cant trabajadores', cant_producida: 'cant producida' },
        { id: 2, Categoria: 'Categoria#2', vitola: 'vitola', brigada: 'brigada', cant_trabajadores: 'cant trabajadores', cant_producida: 'cant producida' }
      ],

      Categoria: '',
      vitola: '',
      brigada: '',
      cant_trabajadores: '',
      cant_producida: '',
      snackbar: false,
      mensaje: '',
      formAgregar: true,
      indexProduccion: '',
      arrayNombreVitola: '',
      arrayBrigada: ''
    }
  },
  // HACER EN LA API METODO PARA BUSCAR TODAS LAS CATEGORIAS Q HAY PARA LLAMARLO, Y TAMBIEN HACER EL DE OBTENER NOMBRES D VITOLAS PASANDOLE LA CATEGORIA
  created() {
    this.autoCompletableBrigadas()
  },
  methods: {
    async autoCompletableNombreVitola() {
      try {

        if (this.Categoria !== '') {
          const response = await axios.get(`http://127.0.0.1:8000/api/vitolasPorCategoria`, {
            params: {
              categoria: this.Categoria,
            }
          });
          this.arrayNombreVitola = response.data.vitolas;
        }
      } catch (error) {
        console.error('Error al obtener las vitolas:', error);
      }
    },

    async autoCompletableBrigadas() {
      try {

        const response = await axios.get(`http://127.0.0.1:8000/api/brigadas`);
        console.log(response.data);
        this.arrayBrigada = response.data.brigadas.map(brigadas => brigadas.numero);

      } catch (error) {
        console.error('Error al obtener las vitolas:', error);
      }
    },




    agregarProduccion() {
      if (this.Categoria === '' || this.vitola === '' || this.brigada === '' || this.cant_trabajadores === '' || this.cant_producida === '') {
        this.snackbar = true
        this.mensaje = 'Llena todos los campos!'
      } else {
        this.listaProducciones.push({
          id: Date.now(),
          Categoria: this.Categoria,
          vitola: this.vitola,
          brigada: this.brigada,
          cant_trabajadores: this.cant_trabajadores,
          cant_producida: this.cant_producida
        })
        this.Categoria = '',
          this.vitola = '',
          this.brigada = '',
          this.cant_trabajadores = ''
        this.cant_producida = ''
        this.snackbar = true
        this.mensaje = 'Produccion creada con exito!'
      }
    },
    eliminarProduccion(id) {
      this.listaProducciones = this.listaProducciones.filter(e => e.id != id)
      this.snackbar = true
      this.mensaje = 'Produccion eliminada correctamente!'
    },
    editarProduccion(index) {
      this.formAgregar = false
      this.Categoria = this.listaProducciones[index].Categoria
      this.vitola = this.listaProducciones[index].vitola
      this.brigada = this.listaProducciones[index].brigada
      this.cant_trabajadores = this.listaProducciones[index].cant_trabajadores
      this.cant_producida = this.listaProducciones[index].cant_producida
      this.indexProduccion = index
    },
    editarProduccionA() {
      this.listaProducciones[this.indexProduccion].Categoria = this.Categoria
      this.listaProducciones[this.indexProduccion].vitola = this.vitola
      this.listaProducciones[this.indexProduccion].brigada = this.brigada
      this.listaProducciones[this.indexProduccion].cant_trabajadores = this.cant_trabajadores
      this.listaProducciones[this.indexProduccion].cant_producida = this.cant_producida
      this.formAgregar = true
      this.Categoria = ''
      this.vitola = ''
      this.brigada = ''
      this.cant_trabajadores = ''
      this.cant_producida = ''
      this.snackbar = true
      this.mensaje = 'Se ha editado correctamente la produccion!'
    }
  },

}


</script>
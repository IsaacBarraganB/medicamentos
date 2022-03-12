<template>
 <q-page class="bg-white-3">
    <div class="q-pa-sm panel-header">
      <div class="row">
        <div class="col-sm-6">
          <div class="q-pa-md q-gutter-sm">
            <q-breadcrumbs align="left" style="font-size: 20px">
              <q-breadcrumbs-el label="" icon="home" to="/" />
              <q-breadcrumbs-el label="Medicamentos" to="/medicine" />
              <q-breadcrumbs-el label="Nuevo" />
            </q-breadcrumbs>
          </div>
        </div>
        <div class="col-xs-12 col-md-6  pull-right">
          <div class="q-pa-sm q-gutter-sm">
          </div>
        </div>
      </div>
    </div>
    <div class="q-pa-md bg-grey-3">
        <div class="row bg-white border-panel">
            <div class="col q-pa-md">
                <div class="row q-col-gutter-xs">
                  <div class="col-xs-12 col-sm-4">
                        <q-input
                            color="dark"
                            bg-color="secondary"
                            filled
                            v-model="medicine.fields.medicine"
                            @filter="csambiar(medicine.fields.medicine)"
                            label="Nombre del medicamento">
                            <template v-slot:prepend>
                                <q-icon name="fas fa-capsules" />
                            </template>
                        </q-input>
                  </div>

                  <div class="col-xs-12 col-sm-12 col-lg-12 q-pt-md text-bottom text-right">
                         <q-btn color="positive" icon="save" label="Guardar" @click="createMedicine()" />
                  </div>
                </div>
            </div>
        </div>
    </div>
  </q-page>
</template>
<script>
// import api from '../../commons/api.js'
import api from 'axios'
import qs from 'qs'
import useVuelidate from '@vuelidate/core'
const { required } = require('@vuelidate/validators')

export default {
  name: 'NewMedicine',
  validations: {
    medicine: {
      fields: {
        medicine: { required }
      }
    }
  },
  setup () {
    return { v$: useVuelidate() }
  },
  data () {
    return {
      medicine: {
        fields: {
          medicine: null
        }
      }
    }
  },
  computed: {
    rule (val) {
      return [
        val => (this.$v.medicine.fields.medicine.required) || '.'
      ]
    },
  },
  methods: {
    csambiar () {
      alert('ahah')
    },
    inputMedicine (v) {
      console.log(v)
    },
    async createMedicine() {
      if (this.medicine.fields.medicine === '' || this.medicine.fields.medicine === null) {
        this.$q.notify({
          message: 'El campo de debe de estar vacio',
          position: 'top',
          color:'warning'
        })
        return false
      }
      this.$q.loading.show()
      const params = {}
      params.name = this.medicine.fields.medicine.toUpperCase()
      await api.post(`http://localhost:8080/create/medicine`, qs.stringify(params)).then(({ data }) => {
        this.$q.notify({
          message: data.message,
          position: 'top',
          color: (data.result ? 'positive' : 'warning')
        })
      })
      this.cleanFields()
      this.$q.loading.hide()
    },
    cleanFields () {
      this.medicine.fields.medicine = null
    }
  }
}
</script>

<style>
</style>

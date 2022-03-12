<template>
 <q-page class="bg-white-3">
    <div class="q-pa-sm panel-header">
      <div class="row">
        <div class="col-sm-6">
          <div class="q-pa-md q-gutter-sm">
            <q-breadcrumbs align="left" style="font-size: 20px">
              <q-breadcrumbs-el label="" icon="home" to="/" />
              <q-breadcrumbs-el label="Medicamento" to="/medicine" />
              <q-breadcrumbs-el :label="medicine.fields.medicine" />
            </q-breadcrumbs>
          </div>
        </div>
        <div class="col-xs-12 col-md-6  text-right">
          <div class="q-pa-sm q-gutter-sm">
              <q-btn color="positive" icon="save" label="Actualizar" @click="updateMedicine()" />
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
                            @input="v => { medicine.fields.medicine = v.toUpperCase() }"
                            label="Nombre">
                            <template v-slot:prepend>
                                <q-icon name="fas fa-capsules" />
                            </template>
                        </q-input>
                  </div>
                </div>
            </div>
        </div>
    </div>
    <div class="q-pa-md bg-grey-3">
        <div class="row bg-white border-panel">
          <div class="col q-pa-md">
            <div class="row q-col-gutter-xs">
              <div class="col-xs-12 col-sm-12">
                <q-tabs
                    v-model="tab"
                    dense
                    class="text-grey"
                    active-color="primary"
                    indicator-color="primary"
                    align="justify"
                    narrow-indicator
                  >
                    <q-tab name="descriptions" label="DESCRIPCIóN" />
                  </q-tabs>
                    <q-tab-panels v-model="tab" animated>
                      <q-tab-panel name="descriptions">
                        <div class="row q-col-gutter-xs">
                        <div class="col-xs-12 col-sm-4">
                        <q-input
                            color="dark"
                            bg-color="secondary"
                            filled
                            v-model="descriptions.fields.name"
                            label="Descripcion">
                            <template v-slot:prepend>
                                <q-icon name="description" />
                            </template>
                        </q-input>
                        </div>
                        <div class="col-xs-12 col-md-12  text-right">
                          <div class="q-pa-sm q-gutter-sm">
                              <q-btn v-if="btnSave" color="positive" icon="save" label="Agregar" @click="addDescription()" />
                              <q-btn v-if="editDescription" color="red" icon="cancel" label="Agregar" @click="cancelEditDescription()" />
                              <q-btn v-if="editDescription" color="green" icon="save" label="Actualizar" @click="updateDescription()" />
                          </div>
                        </div>
                        <div class="col-xs-12 col-sm-12">
                      <q-table
                      flat
                    bordered
                    :rows="data"
                    :columns="columns"
                    row-key="serial"
                    :filter="filter">
                        <template v-slot:top>
                            <div style="width: 100%;">
                                <q-input dense debounce="300" v-model="filter" placeholder="Buscar" @input="v => { filter = v.toUpperCase() }">
                                <template v-slot:append>
                                    <q-icon name="search" />
                                </template>
                                </q-input>
                            </div>
                        </template>
                        <template v-slot:body="props">
                            <q-tr :props="props">
                                <q-td key="name" style="text-align: left;" :props="props">{{ props.row.descriptions }}</q-td>
                                <q-td key="actions" :props="props">
                                <q-btn color="primary" icon="fas fa-edit" flat @click="editSelectedRow(props.row)" size="10px">
                                    <q-tooltip content-class="bg-primary">Editar</q-tooltip>
                                </q-btn>
                                <q-btn color="red" icon="fas fa-trash-alt" flat @click="deleteSelectedRow(props.row.id)" size="10px">
                                    <q-tooltip content-class="bg-red">Eliminar</q-tooltip>
                                </q-btn>
                                </q-td>
                            </q-tr>
                        </template>
                    </q-table>
                    </div>
                        </div>
                      </q-tab-panel>
                  </q-tab-panels>
              </div>
            </div>
          </div>
        </div>
        </div>
  </q-page>
</template>
<script>
import useVuelidate from '@vuelidate/core'
const { required } = require('@vuelidate/validators')
import api from 'axios'
import qs from 'qs'
export default {
  name: 'EditMedicine',
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
      data: [],
      columns: [
        { name: 'name', align: 'center', label: 'NOMBRE', field: 'name', sortable: true },
        { name: 'actions', align: 'center', label: 'ACCIONES', field: 'actions', sortable: false }
      ],
      filter: '',
      tab: 'descriptions',
      medicine: {
        fields: {
          medicine: null
        }
      },
      descriptions: {
        fields: {
          name: null
        }
      },
      editDescription: false,
      btnSave: true,
      description_id: null
    }
  },
  computed: {
    rule (val) {
      return [
        val => (this.$v.medicine.fields.medicine.required) || '.'
      ]
    },
  },
  created() {
    this.getMedicine()
    this.getDescriptions()
  },
  methods: {
    async updateDescription () {
      const params = {}
      params.description = this.descriptions.fields.name.toUpperCase()
      params.id = this.description_id
      this.$q.loading.show()
      await api.post(`http://localhost:8080/update/description`, qs.stringify(params)).then(({ data }) => {
        this.$q.notify({
          message: data.message,
          position: 'top',
          color: (data.result ? 'positive' : 'warning')
        })
        if (data.result) {
          this.getDescriptions()
        }
      })
      this.descriptions.fields.name = null
      this.editDescription = false
      this.btnSave = true
      this.$q.loading.hide()
    },
    cancelEditDescription () {
      this.descriptions.fields.name = null
      this.editDescription = false
      this.btnSave = true
    },
    editSelectedRow (row) {
      this.btnSave = false
      this.editDescription = true
      this.description_id = row.id
      this.descriptions.fields.name = row.descriptions
    },
    async getDescriptions () {
      this.$q.loading.show()
      await api.get(`http://localhost:8080/getDescription/getDescriptionById/${this.$route.params.id}`).then(({ data }) => {
        this.data = data.myData
        console.log(data.myData)
      })
      this.$q.loading.hide()
    },
    async getMedicine () {
      this.$q.loading.show()
      await api.get(`http://localhost:8080/getMedicine/${this.$route.params.id}`).then(({ data }) => {
        this.medicine.fields.medicine = data.myData[0].name
        console.log(data.myData)
      })
      this.$q.loading.hide()
    },
    async addDescription () {
      if (this.descriptions.fields.name === '' || this.descriptions.fields.name === null) {
        this.$q.notify({
          message: 'El campo de debe de estar vacio',
          position: 'top',
          color:'warning'
        })
        return false
      }
      const name = this.descriptions.fields.name.toUpperCase()
      this.$q.loading.show()
      const params = {}
      params.description = this.descriptions.fields.name.toUpperCase()
      params.medicine_id = this.$route.params.id 
      await api.post(`http://localhost:8080/create/description`,qs.stringify(params)).then(({ data }) => {
        this.$q.notify({
          message: data.message,
          position: 'top',
          color: (data.result ? 'positive' : 'warning')
        })
        if (data.result) {
          this.getDescriptions()
        }
      })
      this.descriptions.fields.name = null
      this.$q.loading.hide()
    },
    async updateMedicine() {
      const params = {}
      params.name = this.medicine.fields.medicine.toUpperCase()
      params.id = this.$route.params.id
      this.$q.loading.show()
      await api.post(`http://localhost:8080/update/medicine`, qs.stringify(params)).then(({ data }) => {
        this.$q.notify({
          message: data.message,
          position: 'top',
          color: (data.result ? 'positive' : 'warning')
        })
        if (data.result) {
          this.getMedicine()
        }
      })
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

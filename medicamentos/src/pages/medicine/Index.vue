<template>
  <q-page class="bg-white-3">
      <div class="q-pa-sm panel-header">
      <div class="row">
        <div class="col-sm-6">
          <div class="q-pa-md q-gutter-sm">
            <q-breadcrumbs align="left" style="font-size: 20px">
              <q-breadcrumbs-el label="" icon="home" to="/" />
              <q-breadcrumbs-el label="Medicamentos" />
            </q-breadcrumbs>
          </div>
        </div>
        <div class="col-xs-12 col-md-6  text-right">
          <div class="q-pa-sm q-gutter-sm">
            <q-btn class="bg-primary" style="color: white" icon="add" label="Nuevo" @click="$router.push('/medicine/new')" />
          </div>
        </div>
      </div>
    </div>
    <div class="q-pa-md bg-grey-3">
        <div class="row bg-white border-panel">
            <div class="col q-pa-md">
                <div class="row q-col-gutter-xs">
                    <div class="col-xs-12 col-sm-12">
                      <q-table
                      class="my-sticky-header-table"
                      flat
                    bordered
                    :rows="medicine"
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
                                <q-td key="name" style="text-align: left;" :props="props">{{ props.row.name }}</q-td>
                                <q-td key="actions" :props="props">
                                <q-btn color="primary" icon="fas fa-edit" flat @click="editSelectedRow(props.row.id)" size="10px">
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
            </div>
        </div>
    </div>
  </q-page>
</template>

<script>
import api from 'axios'
export default {
  data () {
    return {
      filter: '',
      medicine: [],
      columns: [
        { name: 'name', align: 'center', label: 'NOMBRE', field: 'name', sortable: true },
        { name: 'actions', align: 'center', label: 'ACCIONES', field: 'actions', sortable: false }
      ],
    }
  },
  created () {
    this.getMedicine()
  },
  methods: {
    editSelectedRow (id) {
      this.$router.push(`/medicine/${id}`)
    },
    async deleteSelectedRow (data) {
      const id = data
      this.$q.dialog({
        message: '¿Desea borrar este Medicamento?',
        ok: {
          label: 'Aceptar',
          color: 'green'
        },
        cancel: {
          label: 'Cancelar',
          color: 'red'
        }
      }).onOk(() => {
        this.$q.loading.show()
        api.post(`http://localhost:8080/delete/medicine/${id}`).then(({ data }) => {
          this.$q.notify({
            message: data.message,
            position: 'top',
            color: (data.result ? 'positive' : 'warning')
          })
          if (data.result) {
            this.getMedicine()
          }
        })
      }).onCancel(() => {})
      this.$q.loading.hide()
    },
    async getMedicine () {
      this.$q.loading.show()
      await api.get('http://localhost:8080/getMedicine').then(({ data }) => {
        this.medicine = data.myData
        console.log(data.myData)
      })
      this.$q.loading.hide()
    }
  },
}
</script>

<style lang="sass">
.my-sticky-header-table
  /* height or max-height is important */
  height: 450px

  .q-table__top,
  .q-table__bottom,
  thead tr:first-child th
    /* bg color is important for th; just specify one */
    background-color: white

  thead tr th
    position: sticky
    z-index: 1
  thead tr:first-child th
    top: 0

  /* this is when the loading indicator appears */
  &.q-table--loading thead tr:last-child th
    /* height of all previous header rows */
    top: 48px
</style>
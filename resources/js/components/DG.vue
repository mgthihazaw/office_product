<template>
  <div>
    <v-card
      class="mx-auto"
      max-width="90%"
      style="margin-top: -64px;"
      max-height="100%"
      height="100%"
    >
      <v-toolbar flat color>
        <v-toolbar-title class="black--text">Keys</v-toolbar-title>

        <v-text-field
          label="Search"
          solo
          hide-details
          dense
          prepend-inner-icon="mdi-magnify"
          rounded
          id="styled-input"
          @input="onQuickFilterChanged"
        ></v-text-field>

        <v-spacer></v-spacer>

        <v-btn icon>
          <v-icon color="primary" dark @click.stop="dialog = true">mdi-plus-circle</v-icon>
        </v-btn>
        <v-btn icon color="warning">
          <v-icon>mdi-content-save</v-icon>
        </v-btn>
        <v-btn icon color="secondary">
          <v-icon>mdi-delete</v-icon>
        </v-btn>
      </v-toolbar>

      <v-divider></v-divider>

      <v-card-text>
        <div>
          <ag-grid-vue
            style="width: 100%; height: 600px"
            class="ag-theme-material"
            id="myGrid"
            ref="myGrid"
            :gridOptions="gridOptions"
            :rowHeight="40"
            @grid-ready="onGridReady"
            :columnDefs="columnDefs"
            :modules="modules"
            :rowModelType="rowModelType"
            :components="components"
            :infiniteInitialRowCount="infiniteInitialRowCount"
            :defaultColDef="defaultColDef"
            :suppressRowClickSelection="true"
            :rowSelection="rowSelection"
            :datasource="datasource"
            maxBlocksInCache="1"
            :animateRows="true"
          ></ag-grid-vue>
        </div>
      </v-card-text>
    </v-card>
    <v-row justify="center">
      <v-dialog v-model="dialog" max-width="800">
        <v-card>
          <v-card-title class="headline">ADD KEY</v-card-title>
          <v-divider></v-divider>
          <v-card-text>
            <v-form ref="form" lazy-validation>
              <v-text-field v-model="form.module_serial" label="Module Serial" required></v-text-field>

              <v-text-field v-model="form.hdd_serial" label="HDD Serial" required></v-text-field>
              <v-text-field v-model="form.hardware_id" label="Hardware ID" required></v-text-field>
              <v-text-field v-model="form.update_key" label="Update Key" required></v-text-field>

              <v-text-field v-model="form.tablet_key" label="Tablet Key" required></v-text-field>
              <v-text-field v-model="form.tabscreen_key" label="Tabscreen Key" required></v-text-field>
              <v-textarea
                v-model="form.client_remark"
                clearable
                clear-icon="cancel"
                label="Client Remark"
              ></v-textarea>
              <v-textarea
                v-model="form.admin_remark"
                clearable
                clear-icon="cancel"
                label="Admin Remark"
              ></v-textarea>
              <v-radio-group v-model="form.paid" row :mandatory="false">
                <v-radio label="Unpaid" value="0"></v-radio>
                <v-radio label="Paid" value="1"></v-radio>
              </v-radio-group>
            </v-form>
          </v-card-text>

          <v-card-actions>
            <v-spacer></v-spacer>

            <v-btn color="grey darken-1" @click="dialog = false">Cancel</v-btn>

            <v-btn color="blue darken-1" @click="addKey">ADD</v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </v-row>
  </div>
</template>
<script>
// import "../node_modules/@ag-grid-community/core/dist/styles/ag-grid.css";
// import "../node_modules/@ag-grid-community/core/dist/styles/ag-theme-balham.css";
// eslint-disable-next-line
import { AgGridVue } from "@ag-grid-community/vue";
// eslint-disable-next-line
import { AllCommunityModules } from "@ag-grid-community/all-modules";
export default {
  components: {
    "ag-grid-vue": AgGridVue
  },
  data() {
    return {
      paid: "0",
      dialog: false,
      mainData: [],
      data: [],
      columnDefs: [],
      modules: AllCommunityModules,
      gridOptions: null,
      datasource: null,
      gridColumnApi: null,
      gridApi: null,
      rowModelType: null,
      infiniteInitialRowCount: null,
      getRowNodeId: null,
      rowSelection: null,
      editData: [],
      components: null,
      form: {
        admin_remark: "",
        client_remark: "",
        created_at: "",
        hardware_id: "",
        hdd_serial: "",
        module_serial: "",
        paid: "0",
        tablet_key: "",
        tabscreen_key: ""
      }
    };
  },
  beforeMount() {
    this.defaultColDef = {
      resizable: true,
      editable: true,
      sortable: true
    };
    this.gridOptions = {};
    this.rowModelType = "infinite";
    this.infiniteInitialRowCount = 90000;
    this.rowSelection = "multiple";
    this.components = {
      loadingRenderer: params => {
        if (params.value !== undefined) {
          return params.value;
        } else {
          return "<img width='30' src='https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcR9M7xvb95rERNwepwumGEKKxyytqjfb21vxat3MamqJehb6noaDQ&s' >";
        }
      }
    };
    this.columnDefs = [
      {
        checkboxSelection: true,
        headerName: "",
        width: 50
      },
      {
        headerName: "ID",
        field: "id",
        valueGetter: "node.id",
        cellRenderer: "loadingRenderer",
        width: 100
      },
      {
        headerName: "Module Serial",
        field: "module_serial",

        width: 150
      },
      {
        headerName: "HDD Serial",
        field: "hdd_serial",
        width: 150
      },
      {
        headerName: "HardwareID ",
        field: "hardware_id",

        width: 150
      },
      {
        headerName: "Update Key",
        field: "update_key",
        width: 150
      },
      {
        headerName: "Tablet Key",
        field: "tablet_key",
        width: 150
      },
      {
        headerName: "Tabscreen Key",
        field: "tabscreen_key",
        width: 150
      },
      {
        headerName: "Client Remark",
        field: "client_remark",
        width: 150
      },
      {
        headerName: "Admin Remark",
        field: "admin_remark",
        width: 150
      },
      {
        headerName: "Paid",
        field: "paid",
        width: 100
      },
      {
        headerName: "Start Date",
        field: "created_at",
        width: 100
      },
      {
        headerName: "End Date",
        field: "updated_at",
        width: 100
      }
    ];

    this.getRowNodeId = item => {
      return item.id.toString();
    };
  },
  mounted() {
    this.gridApi = this.gridOptions.api;
    this.gridColumnApi = this.gridOptions.columnApi;
  },
  methods: {
    onQuickFilterChanged(e) {},
    onGridReady(params) {
      const updateData = data => {
        // console.log(data);
        var datasource = {
          rowCount: null,
          // eslint-disable-next-line
          getRows: function(params) {
            // eslint-disable-next-line
            console.log(
              "asking for " + params.startRow + " to " + params.endRow
            );
            setTimeout(function() {
              var rowsThisPage = data.slice(params.startRow, params.endRow);
              var lastRow = -1;
              if (data.length <= params.endRow) {
                lastRow = data.length;
              }
              params.successCallback(rowsThisPage, lastRow);
            }, 200);
          }
        };
        // eslint-disable-next-line
        params.api.setDatasource(datasource);
      };
      this.data = new Array(100000);
      updateData(this.data);
      // axios.get("/api/keys").then(res => {
      //   // eslint-disable-next-line
      //   console.log("res");

      //   this.data = res.data.keys;
      //   updateData(this.data);
      // });
    },
    addKey() {
      axios.post("/api/keys", this.form).then(res => {
        console.log(res);
        this.data.splice(0, 0, res.data);
        this.gridApi.refreshInfiniteCache();
        this.dialog = false;
      });

      // this.data.splice(0, 0, newDataItems);
      // this.gridApi.updateRowData({
      //   addIndex: 1,
      //   add: newDataItems
      // });
      // this.gridApi.refreshInfiniteCache();
    }
  }
};
</script>
<style>
.v-text-field.v-text-field--solo:not(.v-text-field--solo-flat)
  > .v-input__control
  > .v-input__slot {
  width: 250px;
  margin-left: 20px;
}
.collection-item {
  padding-top: 10px;
  padding-left: 20px;
}
.collection {
  width: 200px;
  border: 1px solid grey;
  border-radius: 10px;
}
.collection-header {
  padding-top: 10px;
  padding-left: 20px;
}
</style>

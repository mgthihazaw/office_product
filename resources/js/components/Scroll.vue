<template>
  <div>
    <v-card
      class="mx-auto"
      min-width="92%"
      max-width="95%"
      style="margin-top: -80px;"
      max-height="100%"
      height="100%"
    >
      <v-toolbar flat color>
        <v-btn class="mt-1" icon>
          <v-icon color="black" dark @click="refresh">mdi-reload</v-icon>
        </v-btn>
        <v-row class="pt-1">
          <v-col cols="4" sm="3" md="2" xs="3">
            <v-select
              :items="columnKeys"
              item-text="name"
              item-value="field"
              append-icon="mdi-loupe"
              v-model="search.key"
              @input="search.value=''"
              solo
              dense
              hide-details
              label="Column"
              item-disabled="show"
            ></v-select>
          </v-col>
          <v-col
            cols="8"
            md="6"
            sm="8"
            xs="8"
            v-if="search.key !='paid' && search.key !='user_id' && search.key !='created_at' && search.key !='updated_at'"
          >
            <v-text-field
              label="Search"
              solo
              hide-details
              append-icon="mdi-magnify"
              rounded
              dense
              id="styled-input"
              @keyup.enter="onQuickFilterChanged"
              v-model="search.value"
              :disabled="search.key? false : true"
            ></v-text-field>
          </v-col>
          <v-col cols="6" md="6" sm="8" xs="8" v-if="search.key =='paid'">
            <v-select
              :items="[{id : '',name : 'None'},{id : 0,name : 'Not Paid'},{id : 1,name : 'Paid'}]"
              item-text="name"
              item-value="id"
              append-icon="mdi-loupe"
              v-model="search.value"
              @input="onQuickFilterChanged"
              solo
              dense
              hide-details
              label="Search Value"
            ></v-select>
          </v-col>
          <v-col cols="6" md="6" sm="8" xs="8" v-if="search.key =='user_id'">
            <v-select
              :items="users"
              item-text="name"
              item-value="id"
              append-icon="mdi-loupe"
              v-model="search.value"
              @input="onQuickFilterChanged"
              solo
              dense
              hide-details
              label="Search Value"
            ></v-select>
          </v-col>
          <v-col
            cols="6"
            md="6"
            sm="8"
            xs="8"
            v-if="search.key =='created_at' || search.key =='updated_at'"
          >
            <v-menu
              ref="menu"
              v-model="menu"
              :close-on-content-click="false"
              :return-value.sync="search.value"
              transition="scale-transition"
              offset-y
              min-width="290px"
            >
              <template v-slot:activator="{ on }">
                <v-text-field
                  v-model="search.value"
                  label="Picker in menu"
                  prepend-icon="mdi-event"
                  readonly
                  v-on="on"
                  solo
                  dense
                  hide-details
                ></v-text-field>
              </template>
              <v-date-picker v-model="search.value" no-title scrollable>
                <v-spacer></v-spacer>
                <v-btn text color="primary" @click="menu=false ">Cancel</v-btn>
                <v-btn text color="primary" @click="onQuickFilterChanged">OK</v-btn>
              </v-date-picker>
            </v-menu>
          </v-col>
        </v-row>

        <v-spacer></v-spacer>
        <v-btn icon v-if="selectionDatas.length >0" @click.stop="viewMode= true">
          <v-icon color="secondary" dark>mdi-eye</v-icon>
        </v-btn>
        <v-btn icon v-if="rolePermis == 1 " @click="showModal">
          <v-icon color="primary" dark>mdi-plus-circle</v-icon>
        </v-btn>
        <v-btn icon color="warning" :disabled="editData.length < 1" @click="saveData">
          <v-icon>mdi-content-save</v-icon>
        </v-btn>
        <v-btn icon color="red" v-show="rolePermis == 1" v-if="!deleteMode" @click="multipleDelete">
          <v-icon>mdi-delete</v-icon>
        </v-btn>
      </v-toolbar>

      <v-divider></v-divider>

      <v-card-text style="width: 100%; height: 650px">
        <div v-if="show">
          <ag-grid-vue
            style="width: 100%; height: 600px;"
            class="ag-theme-material"
            id="myGrid"
            ref="myGrid"
            :gridOptions="gridOptions"
            :rowHeight="40"
            @grid-ready="onGridReady"
            :columnDefs="columnDefs"
            :modules="modules"
            :infiniteInitialRowCount="gridData.count"
            :rowModelType="gridData.rowModelType"
            :defaultColDef="gridData.defaultColDef"
            :components="components"
            :rowSelection="gridData.rowSelection"
            :suppressRowClickSelection="true"
            maxBlocksInCache="2"
            maxConcurrentDatasourceRequests="1"
            paginationPageSize="100"
            :animateRows="true"
            enableCellTextSelection="true"
            @selection-changed="onSelectionChanged"
            @cellValueChanged="cellValueChanged"
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
                v-if="rolePermis ==0"
              ></v-textarea>
              <v-textarea
                v-model="form.admin_remark"
                clearable
                clear-icon="cancel"
                label="Admin Remark"
              ></v-textarea>
              <v-select
                v-if="this.rolePermis == 1"
                :items="users"
                item-text="name"
                item-value="id"
                label="User"
                v-model="form.user_id"
                dense
              ></v-select>
              <v-radio-group v-model="form.paid" row :mandatory="false">
                <v-radio label="Unpaid" value="0"></v-radio>
                <v-radio label="Paid" value="1"></v-radio>
              </v-radio-group>
            </v-form>
          </v-card-text>

          <v-card-actions>
            <v-spacer></v-spacer>

            <v-btn color="grey darken-1" @click="dialog = false">Cancel</v-btn>

            <v-btn
              color="blue darken-1"
              @click="addKey"
              :disabled="!form.user_id && rolePermis == 1"
            >ADD</v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </v-row>
    <v-row justify="center">
      <v-dialog v-model="viewMode" max-width="800">
        <v-card>
          <v-card-title
            class="text-xs-center justify-center title black--text font-weight-bold"
            style="background-color:#00BFA5;"
          >
            Key Viewer
            <v-spacer></v-spacer>
            <v-btn icon>
              <v-icon color="grey darken-1" @click="viewMode = false">mdi-close-circle</v-icon>
            </v-btn>
          </v-card-title>

          <v-card-text>
            <v-list v-for="(data,index) in selectionDatas" :key="index">
              <v-list-item>
                <v-list-item-content>
                  <v-list-item-subtitle>ID</v-list-item-subtitle>
                </v-list-item-content>

                <v-list-item-content>
                  <p class="justify-center subtitle-1">{{data.id}}</p>
                </v-list-item-content>
              </v-list-item>
              <v-list-item v-if="data.username">
                <v-list-item-content>
                  <v-list-item-subtitle>RECEIVED USER</v-list-item-subtitle>
                </v-list-item-content>

                <v-list-item-content>
                  <p class="justify-center subtitle-1">{{data.username}}</p>
                </v-list-item-content>
              </v-list-item>
              <v-list-item>
                <v-list-item-content>
                  <v-list-item-subtitle>Module Serial</v-list-item-subtitle>
                </v-list-item-content>

                <v-list-item-content>
                  <p class="justify-center subtitle-1">{{data.module_serial }}</p>
                </v-list-item-content>
              </v-list-item>
              <v-list-item>
                <v-list-item-content>
                  <v-list-item-subtitle>HDD SERIAL</v-list-item-subtitle>
                </v-list-item-content>

                <v-list-item-content>
                  <p class="justify-center subtitle-1">{{data.hdd_serial}}</p>
                </v-list-item-content>
              </v-list-item>
              <v-list-item>
                <v-list-item-content>
                  <v-list-item-subtitle>HARDWAREID</v-list-item-subtitle>
                </v-list-item-content>

                <v-list-item-content>
                  <p class="justify-center subtitle-1">{{data.hardware_id}}</p>
                </v-list-item-content>
              </v-list-item>
              <v-list-item>
                <v-list-item-content>
                  <v-list-item-subtitle>UPDATE KEY</v-list-item-subtitle>
                </v-list-item-content>

                <v-list-item-content>
                  <p class="justify-center subtitle-1">{{data.update_key}}</p>
                </v-list-item-content>
              </v-list-item>
              <v-list-item>
                <v-list-item-content>
                  <v-list-item-subtitle>TABLET KEY</v-list-item-subtitle>
                </v-list-item-content>

                <v-list-item-content>
                  <p class="justify-center subtitle-1">{{data.tablet_key}}</p>
                </v-list-item-content>
              </v-list-item>
              <v-list-item>
                <v-list-item-content>
                  <v-list-item-subtitle>TABSCREEN KEY</v-list-item-subtitle>
                </v-list-item-content>

                <v-list-item-content>
                  <p class="justify-center subtitle-1">{{data.tabscreen_key}}</p>
                </v-list-item-content>
              </v-list-item>
              <v-list-item>
                <v-list-item-content>
                  <v-list-item-subtitle>CLIENT REMARK</v-list-item-subtitle>
                </v-list-item-content>

                <v-list-item-content>
                  <p class="justify-center subtitle-1">{{data.client_remark}}</p>
                </v-list-item-content>
              </v-list-item>
              <v-list-item>
                <v-list-item-content>
                  <v-list-item-subtitle>ADMIN REMARK</v-list-item-subtitle>
                </v-list-item-content>

                <v-list-item-content>
                  <p class="justify-center subtitle-1">{{data.admin_remark}}</p>
                </v-list-item-content>
              </v-list-item>
              <v-list-item>
                <v-list-item-content>
                  <v-list-item-subtitle>PAID</v-list-item-subtitle>
                </v-list-item-content>

                <v-list-item-content>
                  <v-list-item-title>
                    <v-chip
                      class="text-center"
                      color="red"
                      style="height: 20px;"
                      v-if="data.paid ==0"
                    >
                      <span style="width:150px;">Not Paid</span>
                    </v-chip>
                    <v-chip
                      class="text-center"
                      color="green"
                      style="height: 20px;"
                      v-if="data.paid ==1"
                    >
                      <span style="width:150px;">Paid</span>
                    </v-chip>
                  </v-list-item-title>
                </v-list-item-content>
              </v-list-item>
              <v-list-item>
                <v-list-item-content>
                  <v-list-item-subtitle>START DATE</v-list-item-subtitle>
                </v-list-item-content>

                <v-list-item-content>
                  <v-list-item-title>{{data.created_at}}</v-list-item-title>
                </v-list-item-content>
              </v-list-item>
              <v-list-item>
                <v-list-item-content>
                  <v-list-item-subtitle>END DATE</v-list-item-subtitle>
                </v-list-item-content>

                <v-list-item-content>
                  <v-list-item-title>{{data.updated_at}}</v-list-item-title>
                </v-list-item-content>
              </v-list-item>
              <v-divider></v-divider>
            </v-list>
          </v-card-text>
        </v-card>
      </v-dialog>
    </v-row>
  </div>
</template>
<script>
// eslint-disable-next-line
import { AgGridVue } from "@ag-grid-community/vue";
// eslint-disable-next-line
import { AllCommunityModules } from "@ag-grid-community/all-modules";
import { getLocalUser } from "./../helpers/auth_helper";
let source;
const auth = getLocalUser();

export default {
  components: {
    "ag-grid-vue": AgGridVue
  },
  data() {
    return {
      rolePermis: auth.user.role,
      editData: [],
      selectionDatas: [],
      columnDefs: [],
      modules: AllCommunityModules,
      gridOptions: null,
      datasource: null,
      gridColumnApi: null,
      gridApi: null,
      getRowNodeId: null,
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
        tabscreen_key: "",
        user_id: ""
      },
      columnKeys: [
        { name: "id", field: "id", show: false },
        { name: "user", field: "user_id", show: !auth.user.role },
        { name: "module_serial", field: "module_serial", show: false },
        { name: "hdd_serial", field: "hdd_serial", show: false },
        { name: "hardware_id", field: "hardware_id", show: false },
        { name: "update_key", field: "update_key", show: false },
        { name: "tablet_key", field: "tablet_key", show: false },
        { name: "tabscreen_key", field: "tabscreen_key", show: false },
        { name: "paid", field: "paid", show: false },
        { name: "Start Date", field: "created_at", show: false },
        { name: "End Date", field: "updated_at", show: false }
      ],
      search: {
        key: "",
        value: ""
      },
      menu: false,
      activeMode: false,
      deleteMode: true,
      viewMode: false,
      dialog: false
    };
  },
  created() {
    this.$store.dispatch("key/count");
    this.$store.dispatch("user/getUsers");
  },

  beforeMount() {
    this.gridOptions = {};

    this.components = {
      loadingRenderer: params => {
        if (params.value !== undefined) {
          return params.value;
        } else {
          return "<div class='loader'></div>";
        }
      },
      paidCellEditor: getPaidCellEditor()
    };
    this.columnDefs = [
      {
        checkboxSelection: true,
        headerName: "",
        width: 50,
        editable: false
      },
      {
        headerName: "No",
        valueGetter: "node.id",
        cellRenderer: "loadingRenderer",
        width: 90,
        editable: false
      },
      {
        headerName: "ID",
        field: "id",

        width: 90,
        editable: false
      },
      {
        headerName: "RECEIVED USER",
        field: "username",
        width: 150,
        editable: false,
        hide: this.rolePermis == 0
      },
      {
        headerName: "MODULE SERIAL",
        field: "module_serial",
        editable: this.rolePermis == 1,
        width: 150
      },
      {
        headerName: "HDD SERIAL",
        field: "hdd_serial",
        width: 150,
        editable: true
      },
      {
        headerName: "HARDWARE ID ",
        field: "hardware_id",
        width: 150,
        editable: true
      },
      {
        headerName: "UPDATE KEY",
        field: "update_key",
        width: 150,
        editable: this.rolePermis == 1
      },
      {
        headerName: "TABLET KEY",
        field: "tablet_key",
        width: 150,
        editable: this.rolePermis == 1
      },
      {
        headerName: "TABSCREEN KEY",
        field: "tabscreen_key",
        width: 150,
        editable: this.rolePermis == 1
      },
      {
        headerName: "CLIENT REMARK",
        field: "client_remark",
        width: 150,
        editable: this.rolePermis == 0
      },
      {
        headerName: "ADMIN REMARK",
        field: "admin_remark",
        width: 150,
        editable: this.rolePermis == 1
      },
      {
        headerName: "PAID",
        field: "paid",
        width: 100,
        cellEditor: "paidCellEditor",
        cellRenderer: function(params) {
          if (params.value !== undefined) {
            if (params.value == 1) {
              return `<div style="color:green;">Paid</div>`;
            } else {
              return `<div style="color:red;">Not Paid</div>`;
            }
          } else {
            return "";
          }
        }
      },
      {
        headerName: "START DATE",
        field: "created_at",
        width: 130,
        editable: false,
        valueFormatter: dateFormater
      },
      {
        headerName: "END DATE",
        field: "updated_at",
        width: 130,
        editable: false,
        valueFormatter: dateFormater
      }
    ];

    this.getRowNodeId = item => {
      return item.id.toString();
    };
  },

  methods: {
    showModal() {
      this.dialog = true;
    },
    onQuickFilterChanged() {
      this.$store.commit("key/load");
      this.$store.dispatch("key/count", this.search);
    },
    onGridReady(params) {
      this.gridApi = this.gridOptions.api;
      this.gridColumnApi = this.gridOptions.columnApi;
      const that = this;
      const updateData = data => {
        var datasource = {
          rowCount: null,

          getRows: function(params) {
            // console.log(
            //   "asking for " + params.startRow + " to " + params.endRow
            // );
            if (source) {
              source.cancel("Operation canceled by the user.");
            }
            const CancelToken = axios.CancelToken;
            source = CancelToken.source();

            let start = params.startRow;
            let end = params.endRow;
            let searchKey = that.search.key;
            let searchValue = that.search.value;
            if (data[start] == undefined) {
              axios
                .get(
                  `/api/keys?start=${start}&key=${searchKey}&value=${searchValue}`,
                  {
                    cancelToken: source.token
                  }
                )
                .then(res => {
                  // console.log(res);
                  if (res.data.keys.length > 0) {
                    res.data.keys.forEach((element, index) => {
                      data[params.startRow + index] = element;
                    });

                    var rowsThisPage = data.slice(
                      params.startRow,
                      params.endRow
                    );
                    var lastRow = -1;
                    if (data.length <= params.endRow) {
                      lastRow = data.length;
                    }

                    params.successCallback(rowsThisPage, lastRow);
                  }
                })
                .catch(err => {
                  if (axios.isCancel(err)) {
                    console.log("Post Cancel");
                  }
                });
            } else {
              var rowsThisPage = data.slice(params.startRow, params.endRow);
              var lastRow = -1;
              if (data.length <= params.endRow) {
                lastRow = data.length;
              }

              params.successCallback(rowsThisPage, lastRow);
            }
          }
        };
        params.api.setDatasource(datasource);
      };

      updateData(this.data);
    },
    addKey() {
      this.$store.dispatch("key/addKey", this.form);
      this.dialog = false;
      this.form = {
        admin_remark: "",
        client_remark: "",
        created_at: "",
        hardware_id: "",
        hdd_serial: "",
        module_serial: "",
        paid: "0",
        tablet_key: "",
        tabscreen_key: ""
      };
    },

    multipleDelete() {
      if (window.confirm("Are you sure to delete?")) {
        let selectedDatas = [];
        let selectedNodes = [];
        this.gridApi.getSelectedRows().forEach(row => {
          selectedDatas.push(row.id);
        });
        this.gridApi.getSelectedNodes().forEach(node => {
          selectedNodes.push({ index: node.rowIndex });
        });
        selectedNodes = selectedNodes.reverse();

        this.$store.dispatch("key/delete", { selectedDatas, selectedNodes });
        this.deleteMode = true;
        this.selectionDatas = [];
      }
    },
    cellValueChanged(event) {
      if (event.oldValue != event.newValue) {
        let id = null;
        this.editData.forEach((data, index) => {
          if (data.id == event.data.id) {
            id = index;
          }
        });
        if (id != null) {
          this.editData[id] = event.data;
        } else {
          this.editData.push(event.data);
        }
      }
    },
    saveData() {
      this.gridApi.stopEditing();
      this.$store.dispatch("key/save", this.editData);
      this.editData = [];
    },
    onSelectionChanged(param) {
      if (this.gridApi.getSelectedRows().length > 0) {
        this.deleteMode = false;
        this.selectionDatas = this.gridApi.getSelectedRows();
      } else {
        this.deleteMode = true;
        this.selectionDatas = this.gridApi.getSelectedRows();
      }
    },
    refresh() {
      this.search.key = "";
      this.search.value = "";
      this.selectionDatas = [];
      this.editData = [];
      this.viewMode = false;
      this.deleteMode = true;
      this.onQuickFilterChanged();
    }
  },
  computed: {
    show() {
      return this.$store.getters["key/show"];
    },
    data() {
      return this.$store.getters["key/data"];
    },
    gridData() {
      return this.$store.getters["key/count"];
    },
    users() {
      return this.$store.getters["user/users"];
    }
  }
};

function dateFormater(params) {
  if (params.value !== undefined) {
    let date = new Date(params.value);
    return `${date.getFullYear()}-${date.getMonth() + 1}-${date.getDate()}`;
  } else {
    return "";
  }
}
function getPaidCellEditor() {
  function PaidCellEditor() {}
  PaidCellEditor.prototype.getGui = function() {
    return this.eGui;
  };
  PaidCellEditor.prototype.getValue = function() {
    return this.value;
  };
  PaidCellEditor.prototype.isPopup = function() {
    return true;
  };
  // eslint-disable-next-line
  PaidCellEditor.prototype.init = function(params) {
    // eslint-disable-next-line
    this.value = params.value;
    var tempElement = document.createElement("div");
    tempElement.innerHTML =
      '<div class="collection">' +
      '<div class="collection-item"><button id="bt0"  style="color:red;" >NotPaid</button></div>' +
      '<div class="collection-item"><button id="bt1" style="color:green;" >Paid</button></div>' +
      "</div>";
    var that = this;
    [0, 1].forEach(function(year) {
      tempElement
        .querySelector("#bt" + year)
        .addEventListener("click", function() {
          that.value = year;
          // eslint-disable-next-line
          params.stopEditing();
        });
    });
    this.eGui = tempElement.firstChild;
  };
  return PaidCellEditor;
}
</script>
<style >
.collection-item
  .v-text-field.v-text-field--solo:not(.v-text-field--solo-flat)
  > .v-input__control
  > .v-input__slot {
  width: 100%;
  margin-left: 20px;
}
.loader {
  margin-top: 20px;
  border: 1px solid rgb(218, 229, 230);
  border-radius: 50%;
  border-top: 2px solid rgb(83, 92, 91);
  width: 12px;
  height: 12px;
  -webkit-animation: spin 0.5s linear infinite; /* Safari */
  animation: spin 0.5s linear infinite;
}

/* Safari */
@-webkit-keyframes spin {
  0% {
    -webkit-transform: rotate(0deg);
  }
  100% {
    -webkit-transform: rotate(360deg);
  }
}

@keyframes spin {
  0% {
    transform: rotate(0deg);
  }
  100% {
    transform: rotate(360deg);
  }
}
.collection-item {
  padding-top: 10px;

  padding-bottom: 10px;

  text-align: center;
}

.collection {
  width: 100px;
}
.collection-header {
  padding-top: 10px;
  padding-left: 20px;
}
</style>

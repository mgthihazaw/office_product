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
        <v-btn icon @click="insert(1)">
          <v-icon color="primary">mdi-plus-circle</v-icon>
        </v-btn>
        <v-btn icon @click="saveData" color="warning">
          <v-icon>mdi-content-save</v-icon>
        </v-btn>
        <v-btn icon @click="multipleDelete" color="secondary">
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
            :enterMovesDownAfterEdit="true"
            :enterMovesDown="true"
            @selection-changed="onSelectionChanged"
            @cellValueChanged="cellValueChanged"
            maxBlocksInCache="1"
            :animateRows="true"
          ></ag-grid-vue>
        </div>
      </v-card-text>
    </v-card>
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
      mainData: [],
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
      editData: []
    };
  },
  beforeMount() {
    this.defaultColDef = {
      resizable: true,
      editable: true,
      sortable: true
    };
    this.gridOptions = {};
    this.columnDefs = [
      {
        checkboxSelection: true,
        headerName: "",
        width: 50,
        editable: false
      },
      {
        headerName: "Item ID",
        field: "id",
        valueGetter: "node.id",
        cellRenderer: "loadingRenderer",
        editable: false,
        width: 200,
        sortable: true
      },
      {
        headerName: "Make",
        field: "make",
        width: 500,
        // eslint-disable-next-line
        valueSetter: function(params) {
          // eslint-disable-next-line
          console.log(params);
          // allOfTheData = allOfTheData.filter(function(value, index) {
          //   return selectedDatas.indexOf(value.id) == params.data.id;
          // });

          // this.gridApi.refreshInfiniteCache();
          if (!params.data.make) {
            params.data.make = {};
          }
          params.data.make = params.newValue;
          return true;
        }
      },
      {
        headerName: "Model",
        field: "model",
        cellEditor: "yearCellEditor",
        width: 200
      },
      {
        headerName: "Price",
        field: "price",
        width: 300,
        // eslint-disable-next-line
        valueFormatter: params => {
          // if (typeof params.value === "number") {
          //   return "\xA3" + params.value.toLocaleString();
          // } else {
          //   return params.value;
          // }
        },
        valueSetter: function(params) {
          if (!params.data.price) {
            params.data.price = {};
          }
          // eslint-disable-next-line
          console.log(params.data);
          params.data.price = params.newValue;
          return true;
        }
      },
      {
        headerName: "Status",
        field: "status",
        // eslint-disable-next-line
        cellRenderer: function(params) {
          if (params.value == 1) {
            return `<div style="color:green;">success</div>`;
          } else {
            return `<div style="color:red;">fail</div>`;
          }
        }
      }
    ];
    this.datasource = {
      rowCount: null,
      // eslint-disable-next-line
      getRows: params => {
        // eslint-disable-next-line
        console.log("asking for " + params.startRow + " to " + params.endRow);
        setTimeout(function() {
          var rowsThisPage = allOfTheData.slice(params.startRow, params.endRow);
          for (var i = 0; i < rowsThisPage.length; i++) {
            var item = rowsThisPage[i];
            var itemCopy = JSON.parse(JSON.stringify(item));
            rowsThisPage[i] = itemCopy;
          }
          var lastRow = -1;
          if (allOfTheData.length <= params.endRow) {
            lastRow = allOfTheData.length;
          }
          params.successCallback(rowsThisPage, lastRow);
        }, 200);
      }
    };
    this.components = {
      loadingRenderer: params => {
        if (params.value !== undefined) {
          return params.value;
        } else {
          return "<img width='30' src='https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcR9M7xvb95rERNwepwumGEKKxyytqjfb21vxat3MamqJehb6noaDQ&s' >";
        }
      },
      yearCellEditor: getYearCellEditor()
    };
    this.rowModelType = "infinite";
    this.infiniteInitialRowCount = 100001;
    this.rowSelection = "multiple";
    this.getRowNodeId = item => {
      return item.id.toString();
    };
  },
  mounted() {
    this.gridApi = this.gridOptions.api;
    this.gridColumnApi = this.gridOptions.columnApi;
  },
  methods: {
    onQuickFilterChanged(e) {
      // eslint-disable-next-line
      console.log(e);
      // eslint-disable-next-line
      allOfTheData = allOfTheData.filter(item => {
        return !item.make.search(e);
      });
      setTimeout(
        function() {
          this.gridApi.refreshInfiniteCache();
          // eslint-disable-next-line
          console.log(allOfTheData);
        }.bind(this),
        2000
      );
    },
    onGridReady(params) {
      // eslint-disable-next-line
      console.log(params);
      // eslint-disable-next-line
      console.log("_________________________");
      // eslint-disable-next-line
      console.log(this.$refs.myGrid);
      sequenceId = 1;
      allOfTheData = [];
      for (var i = 0; i < 100001; i++) {
        allOfTheData.push(createRowData(sequenceId++));
      }
      this.mainData = allOfTheData;
      // params.api.setDatasource(this.datasource);
    },
    // eslint-disable-next-line
    insert(count) {
      var newDataItems = {
        id: 0,
        make: "made in new",
        model: "newModel",
        price: 0
      };
      allOfTheData.splice(0, 0, newDataItems);
      // this.gridApi.updateRowData({
      //   addIndex: 1,
      //   add: newDataItems
      // });
      this.gridApi.refreshInfiniteCache();
    },
    saveData() {
      // eslint-disable-next-line
      console.log(this.editData);
      // this.gridApi.forEachNode(function(rowNode, index) {
      //   if (index >= 5) {
      //     return;
      //   }
      //   var data = rowNode.data;
      //   data.price = Math.floor(Math.random() * 20000 + 20000);
      //   itemsToUpdate.push(data);
      // });
      // var res = this.gridApi.updateRowData({ update: itemsToUpdate });
    },

    // eslint-disable-next-line
    onSelectionChanged(param) {
      // eslint-disable-next-line
      console.log(this.gridApi.getSelectedRows());
    },
    multipleDelete() {
      let selectedDatas = [];
      this.gridApi.getSelectedRows().forEach(row => {
        selectedDatas.push(row.id);
      });
      // eslint-disable-next-line
      console.log(selectedDatas);
      // selectedDatas.forEach((data, index) => {
      //   allOfTheData.splice(start, limit);
      //   this.gridApi.refreshInfiniteCache();
      // });
      // eslint-disable-next-line
      allOfTheData = allOfTheData.filter(function(value, index) {
        return selectedDatas.indexOf(value.id) == -1;
      });

      this.gridApi.refreshInfiniteCache();
      this.gridApi.deselectAll();
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

        var newDataItems = event.data;
        allOfTheData.splice(event.rowIndex, 1, newDataItems);

        this.gridApi.refreshInfiniteCache();
      }
    }
  }
};

/*JAVASCRIPT FUNCTION */

var sequenceId;

var allOfTheData;

function createRowData(id) {
  var makes = ["Toyota", "Ford", "Porsche", "Chevy", "Honda", "Nissan"];
  var models = ["2006", "2008", "2010", "2012"];
  return {
    drawer: true,
    id: id,
    make: makes[id % makes.length],
    model: models[id % models.length],
    price: 72000,
    status: Math.round(Math.random())
  };
}

// eslint-disable-next-line
function insertItemsAt2(count) {
  var newDataItems = [];
  for (var i = 0; i < count; i++) {
    var newItem = createRowData(sequenceId++);
    allOfTheData.splice(0, 0, newItem);
    newDataItems.push(newItem);
  }
  return newDataItems;
}
function getYearCellEditor() {
  function YearCellEditor() {}
  YearCellEditor.prototype.getGui = function() {
    return this.eGui;
  };
  YearCellEditor.prototype.getValue = function() {
    return this.value;
  };
  YearCellEditor.prototype.isPopup = function() {
    return true;
  };
  // eslint-disable-next-line
  YearCellEditor.prototype.init = function(params) {
    // eslint-disable-next-line
    this.value = params.value;
    var tempElement = document.createElement("div");
    tempElement.innerHTML =
      '<div class="collection">' +
      "<div class='collection-header'>Choose Model</div>" +
      '<div class="collection-item"><button id="bt2006" >2006</button></div>' +
      '<div class="collection-item"><button id="bt2008" >2008</button></div>' +
      '<div class="collection-item"><button id="bt2010">2010</button></div>' +
      '<div class="collection-item"><button id="bt2012" >2012</button></div>' +
      "</div>";
    var that = this;
    [2006, 2008, 2010, 2012].forEach(function(year) {
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
  return YearCellEditor;
}
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

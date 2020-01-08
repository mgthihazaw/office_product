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
                    <v-icon color="primary" dark @click.stop="dialog = true"
                        >mdi-plus-circle</v-icon
                    >
                </v-btn>
                <v-btn
                    icon
                    color="warning"
                    :disabled="editData.length < 1"
                    @click="saveData"
                >
                    <v-icon>mdi-content-save</v-icon>
                </v-btn>
                <v-btn
                    icon
                    color="secondary"
                    :disabled="deleteMode"
                    @click="multipleDelete"
                >
                    <v-icon>mdi-delete</v-icon>
                </v-btn>
            </v-toolbar>

            <v-divider></v-divider>

            <v-card-text>
                <div v-if="show">
                    <ag-grid-vue
                        style="width: 100%; height: 650px"
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
                        maxBlocksInCache="2"
                        maxConcurrentDatasourceRequests="1"
                        paginationPageSize="100"
                        :animateRows="true"
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
                            <v-text-field
                                v-model="form.module_serial"
                                label="Module Serial"
                                required
                            ></v-text-field>

                            <v-text-field
                                v-model="form.hdd_serial"
                                label="HDD Serial"
                                required
                            ></v-text-field>
                            <v-text-field
                                v-model="form.hardware_id"
                                label="Hardware ID"
                                required
                            ></v-text-field>
                            <v-text-field
                                v-model="form.update_key"
                                label="Update Key"
                                required
                            ></v-text-field>

                            <v-text-field
                                v-model="form.tablet_key"
                                label="Tablet Key"
                                required
                            ></v-text-field>
                            <v-text-field
                                v-model="form.tabscreen_key"
                                label="Tabscreen Key"
                                required
                            ></v-text-field>
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
                            <v-radio-group
                                v-model="form.paid"
                                row
                                :mandatory="false"
                            >
                                <v-radio label="Unpaid" value="0"></v-radio>
                                <v-radio label="Paid" value="1"></v-radio>
                            </v-radio-group>
                        </v-form>
                    </v-card-text>

                    <v-card-actions>
                        <v-spacer></v-spacer>

                        <v-btn color="grey darken-1" @click="dialog = false"
                            >Cancel</v-btn
                        >

                        <v-btn color="blue darken-1" @click="addKey">ADD</v-btn>
                    </v-card-actions>
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

let source;
// let cancel = false;

export default {
    components: {
        "ag-grid-vue": AgGridVue
    },
    data() {
        return {
            load: false,
            last: 100,
            show: false,
            activeMode: false,
            deleteMode: true,
            paid: "0",
            dialog: false,
            mainData: [],
            editData: [],
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
    created() {
        axios.get("/api/keys/count").then(res => {
            this.show = true;
            this.data = new Array(res.data.count);
            this.infiniteInitialRowCount = res.data.count;
        });
    },

    beforeMount() {
        this.gridOptions = {};
        this.defaultColDef = {
            editable: true
        };

        this.rowModelType = "infinite";

        this.rowSelection = "multiple";
        this.components = {
            loadingRenderer: params => {
                if (params.value !== undefined) {
                    return params.value;
                } else {
                    return "<div class='loader'></div>";
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
        // this.gridApi = this.gridOptions.api;
        // this.gridColumnApi = this.gridOptions.columnApi;
    },
    methods: {
        onQuickFilterChanged(e) {},
        onGridReady(params) {
            this.gridApi = this.gridOptions.api;
            this.gridColumnApi = this.gridOptions.columnApi;
            const updateData = data => {
                var datasource = {
                    rowCount: null,

                    getRows: function(params) {
                        console.log(
                            "asking for " +
                                params.startRow +
                                " to " +
                                params.endRow
                        );
                        if (source) {
                            source.cancel("Operation canceled by the user.");
                        }
                        const CancelToken = axios.CancelToken;
                        source = CancelToken.source();

                        let start = params.startRow;
                        let end = params.endRow;

                        if (data[start] == undefined) {
                            axios
                                .get(`/api/keys?start= ${start}&end= ${end}`, {
                                    cancelToken: source.token
                                })
                                .then(res => {
                                    console.log(res);
                                    if (res.data.keys.length > 0) {
                                        res.data.keys.forEach(
                                            (element, index) => {
                                                data[
                                                    params.startRow + index
                                                ] = element;
                                            }
                                        );

                                        var rowsThisPage = data.slice(
                                            params.startRow,
                                            params.endRow
                                        );
                                        var lastRow = -1;
                                        if (data.length <= params.endRow) {
                                            lastRow = data.length;
                                        }

                                        params.successCallback(
                                            rowsThisPage,
                                            lastRow
                                        );
                                    }
                                })
                                .catch(err => {
                                    console.log(err);
                                    if (axios.isCancel(err)) {
                                        console.log("Post Cancel");
                                    }
                                });
                        } else {
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
                    }
                };
                params.api.setDatasource(datasource);
            };

            updateData(this.data);
        },
        addKey() {
            axios.post("/api/keys", this.form).then(res => {
                this.show = false;
                console.log(res);
                this.data.splice(0, 0, res.data.data);

                this.dialog = false;

                var rowCount = this.gridApi.getInfiniteRowCount();
                this.gridApi.setInfiniteRowCount(rowCount + 1);
                // console.log(rowCount);
                this.gridApi.refreshInfiniteCache();
                this.show = true;
                this.$notify({
                    group: "foo",
                    type: "success",
                    title: "ADD KEY",
                    text: "Successfully Create",
                    duration: 1000,

                    speed: 1000
                });
            });
        },
        getData(data) {
            console.log("Hello");
            axios.get("/api/keys", data).then(res => {
                console.log(res.data.keys);
                res.data.keys.forEach((element, index) => {
                    this.data[data.start + index] = element;
                });
            });
        },
        multipleDelete() {
            let selectedDatas = [];
            let selectedNodes = [];
            this.gridApi.getSelectedRows().forEach(row => {
                selectedDatas.push(row.id);
            });
            this.gridApi.getSelectedNodes().forEach(node => {
                console.log(node);
                selectedNodes.push({ index: node.rowIndex });
            });
            selectedNodes = selectedNodes.reverse();
            console.log("Node", selectedNodes);

            // eslint-disable-next-line
            console.log(selectedDatas);
            axios
                .delete("/api/keys/delete", { params: { keys: selectedDatas } })
                .then(res => {
                    selectedNodes.forEach((node, index) => {
                        this.data.splice(node.index, 1);
                    });

                    var rowCount = this.gridApi.getInfiniteRowCount();
                    this.gridApi.setInfiniteRowCount(
                        rowCount - selectedDatas.length
                    );
                    this.gridApi.refreshInfiniteCache();
                    this.gridApi.deselectAll();
                    this.show = false;
                    this.show = true;
                    this.$notify({
                        group: "foo",
                        type: "success",
                        title: "DELETE KEY",
                        text: "Successfully Delete",
                        duration: 1000,

                        speed: 1000
                    });
                });
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
                this.data.splice(event.rowIndex, 1, newDataItems);

                this.gridApi.refreshInfiniteCache();
            }
        },
        saveData() {
            console.log("SaveData =>", this.editData);
            axios
                .put("/api/keys/update", { editData: this.editData })
                .then(res => {
                    console.log(res);
                    this.editData = [];
                    this.$notify({
                        group: "foo",
                        type: "success",
                        title: "SAVE KEY",
                        text: "Successfully Save",
                        duration: 1000,

                        speed: 1000,
                        closeOnClick: true
                    });
                });
        },
        onSelectionChanged(param) {
            if (this.gridApi.getSelectedRows().length > 0) {
                this.deleteMode = false;
            } else {
                this.deleteMode = true;
            }
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
</style>

import { Component, OnInit, ViewChild, ElementRef, AfterViewInit } from '@angular/core';
import { ProductService } from 'src/app/service/product/product.service';
import { AlertService } from 'src/app/service/alert/alert.service';
import { Product } from 'src/app/interface/product';
import { BsModalService, BsModalRef } from 'ngx-bootstrap/modal';
import { ProductCreateComponent } from 'src/app/product-create/product-create.component';
import { SelectionType } from '@swimlane/ngx-datatable';
import { TITLE } from '../constants';

@Component({
  selector: 'app-product-view',
  templateUrl: './product-view.component.html',
  styleUrls: ['./product-view.component.scss']
})

export class ProductViewComponent implements OnInit {
  title = 'Product View Component';
  @ViewChild('ActionsTemplate', { static: true }) ActionsTemplate: ElementRef; //actions defined for each row as edit or delete operation

  public bsModalRef: BsModalRef; //reference to the currently opened modal instance
  public selection: SelectionType; //select all option

  isEditMode: boolean; //flag to control the forms functionality during edit and create

  rows= []; //each record is treated as rows 
  columns: any; //columns of ngx-datatable
  insertId:any; //extract the last element from an array and get its id 

  parentData : Product[] = []; //data from parent getting passed to child
  selectedRow : Product[] = []; //selected rows for multi selection for delete operation  
  selectedIds : Product[] = []; //multiselected Ids for delete operation
  
  pageSize: number; //records per page 
  selected: Product[] = []; //selected rows to be passed to ngx-datatable
  filteredData: Product[]; // this carrys the rows, row values 
  searchText: string; //used for filter in the page 

  pageTitle: string = TITLE; //title fetched from constants file 

  constructor(
    private Product: ProductService, //via httpClient requests are alligned 
    private Alert: AlertService, //using sweetalert2 and toast have handled displaying alerts,
    public modalService: BsModalService //a service provided by the ngx-bootstrap library for managing modal dialogs in Angular applications
  ) { this.pageSize = 5;  //default set the page count to display 5 records per page
      this.selection = SelectionType.checkbox; //assigns the value SelectionType.checkbox to the selection property, SelectionType.checkbox indicates that the data table should use checkboxes for selection
      this.filteredData = this.rows; };

  ngOnInit() {
      //ngx-datatable field input, here since we have made sortable is true, all the columns are sortable, Action has edit and delete
      this.columns = [
        { sno: 1, prop: "selected", name: "", sortable: false, canAutoResize: false, draggable: false, resizable: false, headerCheckboxable: true, checkboxable: true, width: 30 },
     
        { sno: 2, width: 80, name: 'Id', prop: 'id', filter: false ,sortable: true},
        { sno: 3, width: 80, name: 'Name', prop: 'name', filter: false ,sortable: true},
        { sno: 4, width: 80, name: 'State', prop: 'state', filter: false ,sortable: true},
        { sno: 5, width: 80, name: 'Zip', prop: 'zip', filter: true ,sortable: true},
        { sno: 6, width: 80, name: 'Amount', prop: 'amount', filter: true ,sortable: true},        
        { sno: 7, width: 80, name: 'Quantity', prop: 'qty', filter: true ,sortable: true},
        { sno: 8, width: 80, name: 'Item', prop: 'item', filter: true ,sortable: true},

        { sno: 9, width: 100, name: 'Action', cellTemplate: this.ActionsTemplate, sortable: false }
      ];

    this.onRead();   
    this.isEditMode = false;
  }

  /**
   * @desc call to get all product list
   * @param
   * @return response
   */
  onRead(){
    try {             
      this.Product.readProduct().subscribe({  //read product 
        next: this.successResponseRead.bind(this), //if success==true as a response of backend call
        error: this.handleError.bind(this) //on error as a response of backend call
      });
    } catch (error) {
      console.error('An error occurred:', error);
    }
  }

  /**
   * @desc delete operation by passing id 
   * @param id to delete
   * @return response
   */
  onDelete(getId: Number) { 
    try {
      this.Product.deleteProduct({id:getId}).subscribe({  //delete Product passing id to it
        next: this.successResponseRead.bind(this), //if success==true as a response of backend call
        error: this.handleError.bind(this) //on error as a response of backend call
      });
    } catch (error) {
      console.error('An error occurred:', error);
    }
      
    this.Alert.showToast("Success",  'Successfully Deleted!','green'); //alert via toast
  }

  /**
   * @desc error handling
   * @param response
   * @return message on toast
   */
  handleError(response:Object) { 
    this.Product.handleMessage(response);
  }
  
  /**
   * @desc success response from backend as a api response 
   * @param data
   * @return message on toast based on response 
   */
  successResponseRead(data:Object) { //success response from backend
    console.log(data);
    let result= JSON.parse(JSON.stringify(data)); console.log(result.success);
    if (result.success == "true") { //if true its a success operation
      console.log("ll");
      let rows = result.data; //get the datas 
      if(rows && rows.length > 0){ 
        this.filteredData = rows; //pass it to ngx-datatable  
      }
      else
      {
        this.filteredData = [];
      }

      if(this.isEditMode == true) //on edit operation control the message of toast
      {
        this.Alert.showToast("Success", 'Successfully Updated!','green');
      }
      this.isEditMode = false;
    } 
    else{
      this.Alert.showToast("Warning", result.message,'red');
    }
  }

  /**
   * @desc Handle the select event here. Allows you to handle the select event and call a method when the event is triggered.
   * @param selected row
   * @return 
   */
  onSelect({ selected }: any) {
    this.selected = selected; 
  }

  /**
   * @desc (activate) event binding to listen to the onActivate event
   * @param event captured based on active row in ngx-datatable 
   * @return selectedIds capture the rows selected 
   */
  onActivate(event: any) {
    // Update the selection status when a row is activated
    if (event.type === 'checkbox') { // It's used to conditionally execute the code block if the event is related to a checkbox.
      const checkbox = event.cellElement.querySelector('input'); // It assumes that the checkbox is represented by an <input> element.
      const checked = checkbox.checked; //indicating whether the checkbox is currently checked or not.
      const row = event.row.id; //Retrieves the id property from the event.row object, assuming that each row in the ngx-datatable has an id property.

      if (checked) {
        // Add the row to the selectedRows array
        this.selectedIds.push(row); //adds the row (row ID) to the selectedIds array
      } else {
        // Remove the row from the selectedRows array
        const index = this.selectedIds.indexOf(row); //Retrieves the index of the row in the selectedIds array using the indexOf() method.
        if (index > -1) { //Checks if the row exists in the selectedIds array by ensuring that index is greater than -1
          this.selectedIds.splice(index, 1); //This removes the row ID from the selectedIds array
        }
      }
    }
  }

   /**
   * @desc handle the keydown event 
   * @param event captured based on active row in ngx-datatable 
   * @return selectedRow capture the rows selected 
   */
  onSpace(event: any) {
    if (event.type === 'keydown' && event.keyCode === 32) { // handle the keydown event with a specific key code (32 corresponds to the spacebar)
      event.preventDefault(); //prevents the spacebar key from triggering its usual behavior
      this.selectedRow = [...this.selectedRow, event.row]; //Adds the event.row to the selectedRow array
    }
  }

  /**
   * @desc multiselects delete operation
   * @param 
   * @return message on toast based on response 
   */
  onMultiDelete() {
    let getIds = [];
    for(var i=0;i<this.selected.length;i++){
      getIds.push(this.selected[i].id); //pushing the selected values to the array
    } 

    if(this.selected.length > 0){
      try {   
        this.Product.deleteMultipleProduct({ids:getIds}).subscribe({ //delete multiple products by selecting more than one row
          next: this.successResponseRead.bind(this), //if success==true as a response of backend call 
          error: this.handleError.bind(this) //on error as a response of backend call
        });
      } catch (error) {
        console.error('An error occurred:', error);
      }
      
      this.Alert.showToast("Success",  'Successfully Deleted!','green');
    }
    else
    {
      if(this.rows){
        this.Alert.showToast("Warning", 'Please select a row!','red'); //without selecting any rows if we try to click on delete button
      }
      else
      {
        this.Alert.showToast("Warning",  'Empty records','red');
      }
    }

    //reset the values 
    this.selected = []; 
    this.selectedIds.length = 0;
  }
    
  /**
   * @desc called on add or edit operation to show popup 
   * @param data
   * @return message on toast based on response 
   */
  showPopup(data:Object) {
    let record= JSON.parse(JSON.stringify(data));
    if(record && record.id){
      this.parentData = record;  //passing data for update operation from parent to child
      this.isEditMode = true; //flag for edit operation
    }
    else{
      this.parentData = []; //else unset for add operation 
      this.isEditMode = false; //flag for edit operation
    }
    
    //passing data and edit flag for edit and add operation in child component
    const initialState = {
      childData: this.parentData,
      isEditMode: this.isEditMode
    };

    this.bsModalRef = this.modalService.show(ProductCreateComponent,{ initialState });

    this.bsModalRef.content.recordAdded.subscribe((formValues: Product) => {
      let formValue= JSON.parse(JSON.stringify(formValues));
      if (formValue.flag == "true") { //edit operation flag from child component
          var getId = formValue.record.id; //pass id for update operation       
        try {   
          this.Product.updateProduct({data:formValue.record,id:getId}).subscribe({ //update Product passing id to it
            next: this.successResponseRead.bind(this), //if success==true as a response of backend call
            error: this.handleError.bind(this) //on error as a response of backend call
          });
        } catch (error) {
          console.error('An error occurred:', error);
        }
      }
      else
      { 
        if(this.filteredData.length > 0){
          this.insertId= this.filteredData.slice(-1);
          formValue.record.id = parseInt(this.insertId[0].id)+1; //passing id for add operation
        }
        else{
          formValue.record.id = 1;
        }

        try {       
          this.Product.createProduct({data:formValue.record}).subscribe({ //create Product api call 
            next: this.successResponseRead.bind(this), //if success==true
            error: this.handleError.bind(this) //on error
          });
        } catch (error) {
          console.error('An error occurred:', error);
        }

        this.Alert.showToast("Success",  'Successfully Created!','green');  
      }

      this.bsModalRef.hide();
    });
  }

   /**
   * @desc search operation apply filter by changin to lowercase 
   * @param 
   * @return filteredData
   */
  applyFilter() { 
    if (this.searchText) { //search operation apply filter by changin to lowercase 
      this.filteredData = this.rows.filter(item =>
        Object.values(item).some((val:any) =>
          val.toString().toLowerCase().includes(this.searchText.toLowerCase())
        )
      );
    } else {
      this.filteredData = this.rows;
    }
  }
}

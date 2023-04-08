import { Component, OnInit, ViewChild, Input, AfterViewInit } from '@angular/core';
import { ProductService } from 'src/app/service/product/product.service';
import { fromEvent } from 'rxjs';
import { debounceTime } from 'rxjs/operators';
import { AlertService } from 'src/app/service/alert/alert.service';
import { RootService } from 'src/app/service/root/root.service';
import { Product } from '../product';

@Component({
  selector: 'app-product-view',
  templateUrl: './product-view.component.html',
  styleUrls: ['./product-view.component.scss']
})

export class ProductViewComponent implements OnInit, AfterViewInit {
  @ViewChild('search', { static: false }) search: any;
  @ViewChild('ActionsTemplate', { static: true }) ActionsTemplate: any;

  rows: any;
  columns: any;
  parentData : Product[] = []; //data from parent getting passed to child
  selectedRow : string[] = []; //selected rows for multi selection for delete operation

  filteredData = []; //filter search
  columnsWithSearch :any;
  isPopupVisible = false; //flag to control the forms functionality during edit and create
  totalEntries: number = 0;
  selectedIds:any = []; //multiselected Ids for delete operation

  constructor(
    private Product: ProductService, //via post and get http requests are alligned 
    private Root: RootService, //has get, post and handleMessage handled(toast)
    private Alert: AlertService, //using sweetalert2 and toast have handled displaying alerts
  ) { };

  ngOnInit() {

      //ngx-datatable field input, here since we have made sortable is true, all the columns are sortable, Action has edit and delete
      this.columns = [
        { sno: 1, width: 40, name: '', checkboxable: true, headerCheckboxable: true, sortable: false, canAutoResize: false, draggable: false, resizeable: false },
     
        { sno: 2, width: 80, name: 'Id', prop: 'id', filter: false ,sortable: true,},

        { sno: 3, width: 80, name: 'Name', prop: 'name', filter: false ,sortable: true,},
        { sno: 4, width: 80, name: 'State', prop: 'state', filter: false ,sortable: true,},

        { sno: 5, width: 80, name: 'Zip', prop: 'zip', filter: true ,sortable: true,},
        { sno: 6, width: 80, name: 'Amount', prop: 'amount', filter: true ,sortable: true,},
        
        { sno: 7, width: 80, name: 'Qty', prop: 'qty', filter: true ,sortable: true,},
        { sno: 8, width: 80, name: 'Item', prop: 'item', filter: true ,sortable: true,},

        { sno: 9, width: 100, name: 'Action', cellTemplate: this.ActionsTemplate, sortable: false }
      ];

      try {             
        this.Product.readProduct().subscribe({  //read product 
          next: this.successResponseRead.bind(this), //if success==true
          error: this.handleError.bind(this) //on error
        });
      } catch (error) {
        console.error('An error occurred:', error);
      }
  }

  onDelete(data: any) { //delete operation by passing id
    let getId = data['id'];

      try {  
        this.Product.deleteProduct({id:getId}).subscribe({  //delete Product passing id to it
          next: this.successResponseRead.bind(this), //if success==true
          error: this.handleError.bind(this) //on error
        });
      } catch (error) {
        console.error('An error occurred:', error);
      }
        
      this.Alert.showToast("Success", "Success", 'Successfully Deleted!'); //alert via toast

      try {  
        this.Product.readProduct().subscribe((result: any)=> { //read Product
          this.rows = result;
          this.filteredData = this.rows;
        });
      } catch (error) {
        console.error('An error occurred:', error);
      }
  }

  handleError(response:any) { //error handling
    this.Root.handleMessage(response); //has toast defined in it in rootservice file 
  }
  
  successResponseRead(result:any) {
    if (result.success == true) {
      this.rows = result.data.original;
      if(this.rows.length > 0){
        // for specific columns to be search instead of all you can list them by
        this.columnsWithSearch = Object.keys(this.rows[0]) ? Object.keys(this.rows[0]) : this.rows; 
        this.filteredData = this.rows;
        this.totalEntries = 0; //logic to get the max of product id so that while creating new entry its useful
        for (var i=0 ; i<this.rows.length ; i++) {
            if (this.totalEntries == null || (this.rows[i]['id']) > this.totalEntries)
            this.totalEntries = this.rows[i]['id'];
        }
      }
    } 
  }

  onSelect(selected:any[]) {
    this.selectedRow = selected; //checkbox of multiselect handled vida ngx-datatable 
  }

  onActivate(event: any) {
    if(event.type == "checkbox"){
      this.selectedIds.push(event.row.id); //pass the ids of multiselect for multiple delete operation 
    }
  }

  onSpace(event: any) {
    if (event.type === 'keydown' && event.keyCode === 32) {
      event.preventDefault();
      this.selectedRow = [...this.selectedRow, event.row];
    }
  }

  onMultiDelete() { //multiselects delete operation
    if(this.selectedIds.length > 0){
      for (let i = 0; i < this.selectedIds.length; i++) {
            
        let count = 0;
        
        for (let j = 0; j < this.selectedIds.length; j++)
        {
            if (this.selectedIds[i] == this.selectedIds[j])
                count++;
        }
        if (count % 2 != 0)
          this.selectedIds[i];
      }

      for (let i = 0; i < this.selectedIds.length; i++){
        if(i == this.selectedIds.length-1){
          try {   
            this.Product.deleteProduct({id:this.selectedIds[i]}).subscribe({ 
              next: this.successResponseRead.bind(this),
              error: this.handleError.bind(this)
            });
          } catch (error) {
            console.error('An error occurred:', error);
          }
        }
        else{
            this.Product.deleteProduct({id:this.selectedIds[i]}).subscribe({ 
          });
        }      
      }
      
      this.Alert.showToast("Success", "Success", 'Successfully Deleted!');
    }

    try {  
      this.Product.readProduct().subscribe((result: any)=> {
        this.rows = result;
        this.filteredData = this.rows;
      });
    } catch (error) {
      console.error('An error occurred:', error);
    }    
  }

  ngAfterViewInit(): void {
    // Called after ngAfterContentInit when the component's view has been initialized
    fromEvent(this.search.nativeElement, 'keydown')
      .pipe(
        debounceTime(550),
      )
      .subscribe(value => {
        this.filterDatatable(value);
      });
  }

  showPopup(record:any) {
    if(record && record.id){
      this.parentData = record;  //passing data for update operation from parent to child
    }
    else{
      this.parentData = []; //else unset for add operation 
    }
    this.isPopupVisible = true;
  }

  onRecordAdded(records:any) {
    this.isPopupVisible = false;
    
    if(records.record.id){ 
      var getId = records.record.id; //pass id for update operation 
      
      try {   
        this.Product.updateProduct({data:records.record,id:getId}).subscribe({ //update Product passing id to it
          next: this.successResponseRead.bind(this),  //if success==true
          error: this.handleError.bind(this) //on error
        });
      } catch (error) {
        console.error('An error occurred:', error);
      }

      this.Alert.showToast("Success", "Success", 'Successfully Updated!');
    }
    else
    {
      records.record.id = Number(this.totalEntries)+1; //passing id for add operation, after getting max of id incrementing it

      try {       
        this.Product.createProduct({data:records.record}).subscribe({ //create Product api call 
          next: this.successResponseRead.bind(this), //if success==true
          error: this.handleError.bind(this) //on error
        });
      } catch (error) {
        console.error('An error occurred:', error);
      }

      this.Alert.showToast("Success", "Success", 'Successfully Created!');  
    }
  }
    
  filterDatatable(event: any) {  //filter operation 
    // get the value of the key pressed and convert it to lowercase
    let filter = event.target.value.toLowerCase();

    // assigning filtered matches to the active datatable
    this.rows = this.filteredData.filter(item => {
      // iterate through each row's column data looping
      
      for (let i = 0; i < this.columnsWithSearch.length; i++){
        var colValue : any = null;
        colValue= item[this.columnsWithSearch[i]]; 

        // if no filter OR colvalue is NOT null AND contains the given filter
        if (!filter || (!!colValue && colValue.toString().toLowerCase().indexOf(filter) !== -1)) {
          return true;
        }
        else
        {
          return false;
        }
      }
      return this.columns = this.filteredData; 
    });   
  }
}

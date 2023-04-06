import { Component, OnInit, ViewChild, Input, AfterViewInit } from '@angular/core';
import { CsvService } from 'src/app/csv.service';
import { fromEvent } from 'rxjs';
import { map, debounceTime } from 'rxjs/operators';
import { AlertService } from 'src/app/alert.service';
import { RootService } from 'src/app/root.service';

@Component({
  selector: 'app-csv-display',
  templateUrl: './csv-display.component.html',
  styleUrls: ['./csv-display.component.scss']
})

export class CsvDisplayComponent implements OnInit, AfterViewInit {
  @ViewChild('search', { static: false }) search: any;
  @ViewChild('ActionsTemplate', { static: true }) ActionsTemplate: any;

  rows: any;
  csvData: any;
  columns: any;
  parentData : string[] = [];
  message: any;

  filteredData = [];
  columnsWithSearch :any;
  isPopupVisible = false;
  records : string[] = [];
  eachValue : any;
  notValid : any = "";
  public temp: Array<object> = [];
  totalEntries: number = 0;

  constructor(
    private Csv: CsvService,
    private Root: RootService,
    private Alert: AlertService,
  ) { };

  ngOnInit() {

      //ngx-datatable field input, here since we have made sortable is true, all the columns are sortable
      this.columns = [
        { sno: 1, width: 80, name: 'Id', prop: 'id', filter: false ,sortable: true,},

        { sno: 2, width: 80, name: 'Name', prop: 'name', filter: false ,sortable: true,},
        { sno: 3, width: 80, name: 'State', prop: 'state', filter: false ,sortable: true,},

        { sno: 4, width: 80, name: 'Zip', prop: 'zip', filter: true ,sortable: true,},
        { sno: 5, width: 80, name: 'Amount', prop: 'amount', filter: true ,sortable: true,},
        
        { sno: 5, width: 80, name: 'Qty', prop: 'qty', filter: true ,sortable: true,},
        { sno: 5, width: 80, name: 'Item', prop: 'item', filter: true ,sortable: true,},

        { sno: 6, width: 100, name: 'Action', cellTemplate: this.ActionsTemplate, sortable: false }
      ];

      //read data
      this.Csv.read('').subscribe((res: any)=> {
        this.rows = res;
        
        // for specific columns to be search instead of all you can list them by name
        this.columnsWithSearch = Object.keys(this.rows[0]) ? Object.keys(this.rows[0]) : this.rows; 
        this.filteredData = this.rows;
        this.totalEntries = this.rows.length; //total records count
      });
  }

  onDelete(data: any) { //delete operation by passing id
    var getId = data['id'];

      this.Csv.delete({id:getId}).subscribe((res: any)=> { 
        next: this.successResponse.bind(this),
        Error; this.handleError.bind(this)
      });

      
      this.Alert.showToast("Success", "Success", 'Successfully Deleted!');

      this.Csv.read({data:this.rows}).subscribe((result: any)=> {
        this.rows = result;
        this.filteredData = this.rows;
      });
  }

  handleError(response:any) { //error handling
    this.Root.handleMessage(response);
  }
  

  successResponse(result:any) {
    if (result.success == true) {
      this.rows = result;
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
      this.parentData = [];
    }
    this.isPopupVisible = true;
  }

  onRecordAdded(records:any) {
    this.isPopupVisible = false;
    
    if(records.record.id){ 
      var getId = records.record.id; //pass id for update operation 
      this.Csv.update({data:records.record,id:getId}).subscribe((res: any)=> {
        this.rows = res;
      });

      this.Alert.showToast("Success", "Success", 'Successfully Updated!');
    }
    else
    {
      records.record.id = this.totalEntries+1; //passing id for add operation 

      this.Csv.create({data:records.record}).subscribe((res: any)=> {
        this.rows = res;
      });

      this.Alert.showToast("Success", "Success", 'Successfully Created!');      
    }

    this.Csv.read({data:this.rows}).subscribe((result: any)=> {
      this.rows = result;
      this.filteredData = this.rows;
    });
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

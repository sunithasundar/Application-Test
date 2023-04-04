import { Component, OnInit, ViewChild, AfterViewInit } from '@angular/core';
import { CsvService } from 'src/app/csv.service';
import { fromEvent } from 'rxjs';
import { map, debounceTime } from 'rxjs/operators';

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

  filteredData = [];
  columnsWithSearch :any;
  isPopupVisible = false;
  records : string[] = [];
  eachValue : any;
  notValid : any = "";
  public temp: Array<object> = [];

  constructor(
    private Csv: CsvService,
  ) { };

  ngOnInit() {

      this.columns = [
        { sno: 1, width: 80, name: 'Name', prop: 'Name', filter: true ,sortable: true,},

        { sno: 2, width: 80, name: 'Age', prop: 'Age', filter: true ,sortable: true,},
        { sno: 3, width: 80, name: 'City', prop: 'City', filter: true ,sortable: true,},

        { sno: 4, width: 80, name: 'Phone', prop: 'Phone', filter: true ,sortable: true,},
        { sno: 5, width: 80, name: 'State', prop: 'State', filter: true ,sortable: true,},
        { sno: 6, width: 100, name: 'Action', cellTemplate: this.ActionsTemplate, sortable: false }
      ];

      this.Csv.uploadCsv('').subscribe((res: any)=> {
        this.rows = res;
        
        // for specific columns to be search instead of all you can list them by name
        this.columnsWithSearch = Object.keys(this.rows[0]) ? Object.keys(this.rows[0]) : this.rows;
        this.filteredData = this.rows;
      });
  }

  onDelete(data: any) {
    this.filteredData = this.filteredData.filter((row:any) => row.Name !== data.Name);

    this.Csv.saveCsv({data:this.filteredData}).subscribe((res: any)=> {
      this.rows = res;
    });

    this.Csv.uploadCsv({data:this.rows}).subscribe((result: any)=> {
      this.rows = result;
      this.filteredData = this.rows;
    });
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

  showPopup() {
    this.isPopupVisible = true;
  }

  onRecordAdded(record:any) {
    this.records = [];
    this.records.push(record); 
    this.rows.push(this.records[0]);

    this.isPopupVisible = false;

    this.Csv.saveCsv({data:this.rows}).subscribe((res: any)=> {
      this.rows = res;
    });

    this.Csv.uploadCsv({data:this.rows}).subscribe((result: any)=> {
      this.rows = result;
      this.filteredData = this.rows;
    });
  }
    
  filterDatatable(event: any) { 
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

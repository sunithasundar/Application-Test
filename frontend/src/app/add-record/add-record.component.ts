import { Component } from '@angular/core';
import { EventEmitter, Input, Output } from '@angular/core';
import { FormBuilder, FormGroup, Validators, FormControl } from '@angular/forms';

@Component({
  selector: 'app-add-record',
  templateUrl: './add-record.component.html',
  styleUrls: ['./add-record.component.scss']
})
export class AddRecordComponent {
  record = "";
  @Output() recordAdded = new EventEmitter<{record: string, flag: any}>(); //setting flag to check whether its update or add operation 
  @Input() childData: string[] | undefined;
  
  data:any;

  // @ts-ignore
  myForm: FormGroup;
  toggleFlag:boolean=false;

  constructor(private fb: FormBuilder) {}

  ngOnInit() {

    this.data = this.childData;

    if(this.data.length == 0){
      this.toggleFlag = true; //flag set to true for add operation 
    }
    
    this.myForm = this.fb.group({
      id: new FormControl(this.data.id, []),
      name: new FormControl(this.data.name, [Validators.required, Validators.pattern("^[a-zA-Z ]*$")]),
      state: new FormControl(this.data.state, [Validators.required, Validators.pattern("^[a-zA-Z ]*$")]),
      zip: new FormControl(this.data.zip, [Validators.required, Validators.minLength(5), Validators.maxLength(6), Validators.pattern("^[0-9]*$")]),
      amount: new FormControl(this.data.amount, [Validators.required, Validators.pattern("^[0-9.]*$")]),
      qty: new FormControl(this.data.qty, [Validators.required, Validators.minLength(1), Validators.maxLength(4), Validators.pattern("^[0-9]*$")]),
      item: new FormControl(this.data.item, [Validators.required, Validators.pattern("^[a-zA-Z0-9 ]*$")])
    });
  }

  onCancel(){
    this.myForm.reset();
  }

  onSubmit() {
    this.myForm.markAllAsTouched();
    if (this.myForm.valid) {
      this.record = this.myForm.value; 
      if(this.toggleFlag == true){ 
        this.recordAdded.emit({record:this.record,flag:"true"}); //on true will call add operation 
        this.toggleFlag =false;        
      }
      else
      {
        this.toggleFlag =false;
        this.recordAdded.emit({record:this.record,flag:"false"}); //on false will call edit operation 
      }
    }  
  }
}

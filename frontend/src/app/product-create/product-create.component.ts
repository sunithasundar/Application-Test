import { Component } from '@angular/core';
import { EventEmitter, Input, Output } from '@angular/core';
import { FormBuilder, FormGroup, Validators, FormControl } from '@angular/forms';
import { Product } from 'src/app/interface/product';

@Component({
  selector: 'app-product-create',
  templateUrl: './product-create.component.html',
  styleUrls: ['./product-create.component.scss']
})
export class ProductCreateComponent{
  record = "";
  @Output() recordAdded = new EventEmitter<{record: string, flag: string}>(); // From child passing data to Parent. Setting flag to check whether its update or add operation. 
  @Input() childData: Product[];  //selected values passed from Parent to Child 
  
  // @ts-ignore
  productForm: FormGroup;
  data:any;
  toggleFlag:boolean=false; //default flag set to false for edit and update operations 

  constructor(private fb: FormBuilder) {}

  ngOnInit() {

    this.data = this.childData;

    if(this.data.length == 0){
      this.toggleFlag = true; //flag set to true for add operation 
    }

    //validations go here
    this.productForm = this.fb.group({
      id: new FormControl(this.data.id, []),
      name: new FormControl(this.data.name, [Validators.required, Validators.minLength(5), Validators.maxLength(70), Validators.pattern("^[a-zA-Z ]*$")]),
      state: new FormControl(this.data.state, [Validators.required, Validators.pattern("^[a-zA-Z ]*$")]),
      zip: new FormControl(this.data.zip, [Validators.required, Validators.minLength(5), Validators.maxLength(6), Validators.pattern("^[0-9]*$")]),
      amount: new FormControl(this.data.amount, [Validators.required, Validators.pattern("^[0-9.]*$")]),
      qty: new FormControl(this.data.qty, [Validators.required, Validators.minLength(1), Validators.maxLength(4), Validators.pattern("^[0-9]*$")]),
      item: new FormControl(this.data.item, [Validators.required, Validators.maxLength(70), Validators.pattern("^[a-zA-Z0-9 ]*$")])
    });
  }

  onCancel(){
    this.productForm.reset(); //reset form
  }

  onSubmit() {
    this.productForm.markAllAsTouched();
    if (this.productForm.valid) { //if form is valid 
      this.record = this.productForm.value; //get the values 
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

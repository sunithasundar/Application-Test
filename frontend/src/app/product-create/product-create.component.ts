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
  title = 'Product Create Component';
  record = "";
  @Output() recordAdded = new EventEmitter<{record: string, flag: string}>(); // From child passing data to Parent. Setting flag to check whether its update or add operation. 
  @Input() childData: Product[];  //selected values passed from Parent to Child 
  
  // @ts-ignore
  productForm: FormGroup;
  toggleFlag:boolean=false; //default flag set to false for edit and update operations 
  isDisplayed: boolean;

  constructor(private fb: FormBuilder) {}

  ngOnInit() {

    this.isDisplayed = false;

    if(this.childData && this.childData.length == 0){
      this.toggleFlag = true; //flag set to true for add operation 
    }

    //validations go here
    if(this.childData){ 

    var getValues = JSON.parse(JSON.stringify(this.childData));
    var getId = getValues.id; //to differenciate edit or create operation

    if(getId){
      this.productForm = this.fb.group({
        id: new FormControl(getId, []),
        name: new FormControl(getValues.name, [Validators.required, Validators.minLength(5), Validators.maxLength(70), Validators.pattern("^[a-zA-Z ]*$")]),
        state: new FormControl(getValues.state, [Validators.required, Validators.pattern("^[a-zA-Z ]*$")]),
        zip: new FormControl(getValues.zip, [Validators.required, Validators.minLength(5), Validators.maxLength(6), Validators.pattern("^[0-9]*$")]),
        amount: new FormControl(getValues.amount, [Validators.required, Validators.pattern("^[0-9.]*$")]),
        qty: new FormControl(getValues.qty, [Validators.required, Validators.minLength(1), Validators.maxLength(4), Validators.pattern("^[0-9]*$")]),
        item: new FormControl(getValues.item, [Validators.required, Validators.maxLength(70), Validators.pattern("^[a-zA-Z0-9 ]*$")])
      });
    }
    else
    {
      
      this.productForm = this.fb.group({
        id: new FormControl(getId, []),
        name: new FormControl(this.childData.values.bind('name'), [Validators.required, Validators.minLength(5), Validators.maxLength(70), Validators.pattern("^[a-zA-Z ]*$")]),
        state: new FormControl(this.childData.values.bind('state'), [Validators.required, Validators.pattern("^[a-zA-Z ]*$")]),
        zip: new FormControl(this.childData.values.bind('zip'), [Validators.required, Validators.minLength(5), Validators.maxLength(6), Validators.pattern("^[0-9]*$")]),
        amount: new FormControl(this.childData.values.bind('amount'), [Validators.required, Validators.pattern("^[0-9.]*$")]),
        qty: new FormControl(this.childData.values.bind('qty'), [Validators.required, Validators.minLength(1), Validators.maxLength(4), Validators.pattern("^[0-9]*$")]),
        item: new FormControl(this.childData.values.bind('item'), [Validators.required, Validators.maxLength(70), Validators.pattern("^[a-zA-Z0-9 ]*$")])
      });

      this.productForm.reset();
    }
  }
}

  onCancel()
  {
    if(this.isDisplayed)
    {
      this.isDisplayed = true;
    }else{
      this.isDisplayed = false;
      window.location.reload();
    }
  }    

  onClear(){
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

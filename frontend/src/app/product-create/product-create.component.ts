import { Component, ElementRef } from '@angular/core';
import { EventEmitter, Output,ViewChild } from '@angular/core';
import { FormBuilder, FormGroup, Validators, FormControl } from '@angular/forms';
import { Product } from 'src/app/interface/product';
import { ProductService } from 'src/app/service/product/product.service';
import { BsModalRef } from 'ngx-bootstrap/modal';

@Component({
  selector: 'child',
  templateUrl: './product-create.component.html',
  styleUrls: ['./product-create.component.scss']
})
export class ProductCreateComponent{
  title = 'Product Create Component';
  record = "";

  @ViewChild('modal') modal:ElementRef; //modal popup from parent component getting call to child component 
  @Output() recordAdded = new EventEmitter<{record: string, flag: string}>(); // From child passing data to Parent. Setting flag to check whether its update or add operation. 
  
  childData: Product[]; //getting product information from parent to child via this variable
  isEditMode: boolean; //to check whether its edit or add operation
  
  // @ts-ignore
  productForm: FormGroup;

  constructor(private fb: FormBuilder, private Product: ProductService, public bsModalRef: BsModalRef) {}

  ngOnInit() {

      this.productForm = this.fb.group({
        id:  new FormControl('') ,
        name:  new FormControl(''),//, [Validators.required, Validators.minLength(5), Validators.maxLength(70), Validators.pattern("^[a-zA-Z0-9 ]*[a-zA-Z0-9]+[a-zA-Z0-9 ]*$")]),
        state:  new FormControl(''),//, [Validators.required, Validators.pattern("^[a-zA-Z ]*[a-zA-Z]+[a-zA-Z ]*$")]),
        zip: new FormControl(''),//, [Validators.required, Validators.minLength(4), Validators.maxLength(6), Validators.pattern("^[0-9]*[1-9]+[0-9]*$")]),
        amount: new FormControl(''),//, [Validators.required, Validators.pattern("^[0-9.]*[1-9]+[0-9]*$")]),
        qty: new FormControl(''),//, [Validators.required, Validators.minLength(1), Validators.maxLength(4), Validators.pattern("^[0-9]*[1-9]+[0-9]*$")]),
        item: new FormControl('')//, [Validators.required, Validators.maxLength(50), Validators.pattern("^[a-zA-Z0-9 ]*[a-zA-Z0-9]+[a-zA-Z0-9 ]*$")])
      });

      if(this.isEditMode){ //check if its edit opertaion
        var data = JSON.parse(JSON.stringify(this.childData)); 
        var productId = data.id ? data.id : 1; //to pass product id 

        this.patchValue(data, productId); //patchvalue on edit operation 
      }
  }

  /**
   * @desc function to patchavalue user entered values 
   * @param getData, getId
   * @return updated formControl
   */
  patchValue(getData:Product,getId:number){ 
    this.productForm.controls["name"].setValue(getData.name);
    this.productForm.controls["id"].setValue(getId);
    this.productForm.controls["zip"].setValue(getData.zip);
    this.productForm.controls["state"].setValue(getData.state);
    this.productForm.controls["amount"].setValue(getData.amount);
    this.productForm.controls["qty"].setValue(getData.qty);
    this.productForm.controls["item"].setValue(getData.item);
  }

  /**
   * @desc reset form
   * @param 
   * @return 
   */
  onClear(){
    this.productForm.reset(); //reset form
  }

  /**
   * @desc on submit of the form in popup via emit passing the data to be changed to and flag indicates edit or add operation 
   * @param 
   * @return to parent component with params
   */
  onSubmit() {
    this.productForm.markAllAsTouched();
    if (this.productForm.valid) { //if form is valid 
      if(this.isEditMode==true){  //its a edit operation 
        this.recordAdded.emit({record:this.productForm.value,flag:"true"}); //on true will call edit operation      
      }
      else
      {
        //when empty rows need to pass id as 1
        var getProductId = this.productForm.controls["id"].value ? this.productForm.controls["id"].value : 1;
        this.productForm.controls["id"].setValue(getProductId);
        
        this.recordAdded.emit({record:this.productForm.value,flag:"false"}); //on false will call add operation 
      }
    }  
  }
}

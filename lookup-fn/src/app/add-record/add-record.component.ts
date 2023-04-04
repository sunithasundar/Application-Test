import { Component } from '@angular/core';
import { EventEmitter, Input, Output } from '@angular/core';
import { FormBuilder, FormGroup, Validators, FormArray } from '@angular/forms';

@Component({
  selector: 'app-add-record',
  templateUrl: './add-record.component.html',
  styleUrls: ['./add-record.component.scss']
})
export class AddRecordComponent {
  record = { Name: '', Age: '', City:'', Phone: '' , State:''};
  @Output() recordAdded = new EventEmitter<any>();

  myForm: FormGroup;

  constructor(private fb: FormBuilder) {
    this.myForm = this.fb.group({
      name: ['', [Validators.required, Validators.pattern("^[a-zA-Z ]*$")]],
      age: ['', [Validators.required, Validators.minLength(2), Validators.maxLength(3), Validators.pattern("^[0-9]*$")]],
      city: ['', [Validators.required, Validators.pattern("^[a-zA-Z ]*$")]],
      phone: ['', [Validators.required, Validators.minLength(6), Validators.maxLength(10), Validators.pattern("^[0-9]*$")]],
      state: ['',[Validators.required, Validators.pattern("^[a-zA-Z ]*$")]]
    });
  }

  onSubmit() {
    this.myForm.markAllAsTouched();
    if (this.myForm.valid) {
      this.record = this.myForm.value; 
      this.recordAdded.emit(this.record);
    }  
  }
}

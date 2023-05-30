import { ComponentFixture, TestBed } from '@angular/core/testing';
import { BrowserModule } from '@angular/platform-browser';
import { ReactiveFormsModule, FormGroup, FormControl, Validators } from '@angular/forms'; 
import { HttpClientTestingModule, HttpTestingController } from '@angular/common/http/testing';
import { HttpClientModule, HttpClient } from '@angular/common/http';
import { ProductService } from 'src/app/service/product/product.service';
import { AlertService } from 'src/app/service/alert/alert.service';
import { RootService } from 'src/app/service/root/root.service';
import { NgxDatatableModule } from '@swimlane/ngx-datatable';
import { NO_ERRORS_SCHEMA } from "@angular/core";
import {of} from "rxjs";
import { ProductCreateComponent } from './product-create.component';

describe('ProductCreateComponent', () => {
  let component: ProductCreateComponent;
  let productService: ProductService;
  let fixture: ComponentFixture<ProductCreateComponent>;
  let httpMock: HttpTestingController;
  let productServiceSpy : jasmine.SpyObj<HttpClient>;
  
  beforeEach(async () => {

    productServiceSpy = jasmine.createSpyObj( ['onSubmit']);
    await TestBed.configureTestingModule({
      imports: [ HttpClientModule, HttpClientTestingModule, NgxDatatableModule, ReactiveFormsModule, BrowserModule ],
      declarations: [ ProductCreateComponent ],
      providers: [  ProductService,AlertService, RootService,
        {provide: AlertService, useClass: AlertService},
        {provide: RootService, useClass: RootService}],
      schemas: [ NO_ERRORS_SCHEMA ]
    })
    .compileComponents();

    fixture = TestBed.createComponent(ProductCreateComponent);
    component = fixture.componentInstance;
    productService =  TestBed.inject(ProductService);
    productServiceSpy = TestBed.inject(HttpClient) as jasmine.SpyObj<HttpClient>;

    httpMock = TestBed.inject(HttpTestingController);
    fixture.detectChanges();

     // Create and assign a FormGroup instance
     component.productForm = new FormGroup({
      id: new FormControl('', Validators.required),
      name: new FormControl('', Validators.required),
      state: new FormControl('', Validators.required),
      zip: new FormControl('', Validators.required),
      amount: new FormControl('', Validators.required),
      qty: new FormControl('', Validators.required),
      item: new FormControl('', Validators.required)
    });

    fixture.detectChanges();
  });

  it('should create product create', () => {
    expect(component).toBeTruthy();
  });

  it(`should have as title 'Product View Component'`, () => { //checking for page title
    const fixture = TestBed.createComponent(ProductCreateComponent);
    const app = fixture.componentInstance;
    expect(app.title).toEqual('Product Create Component');
  });

  
  it('Should call submit method', () =>{    //calling onsubmit method 
    let orderData = {id:"1",name:"Liquid Saffron",state:"NY",zip:"08998",amount:"25.43",qty:"7",item:"XCD45300"};
  
    spyOn(component,"onSubmit").and.callFake(() => {
      return of(orderData,true);
    });
    component.onSubmit();
    
    expect(component.onSubmit).toBeTruthy();
  })
  
});

import { ComponentFixture, TestBed } from '@angular/core/testing';
import { BrowserModule } from '@angular/platform-browser';
import { ReactiveFormsModule, FormGroup, FormControl, Validators } from '@angular/forms'; 
import { HttpClientModule } from '@angular/common/http';
import { ProductService } from 'src/app/service/product/product.service';
import { NgxDatatableModule } from '@swimlane/ngx-datatable';
import { NO_ERRORS_SCHEMA } from "@angular/core";
import { ProductCreateComponent } from './product-create.component';

describe('ProductCreateComponent', () => {
  let component: ProductCreateComponent;
  let productService: ProductService;
  let fixture: ComponentFixture<ProductCreateComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [ HttpClientModule, NgxDatatableModule, ReactiveFormsModule, BrowserModule ],
      declarations: [ ProductCreateComponent ],
      providers: [  ProductService],
      schemas: [ NO_ERRORS_SCHEMA ]
    })
    .compileComponents();

    fixture = TestBed.createComponent(ProductCreateComponent);
    component = fixture.componentInstance;
    productService = TestBed.inject(ProductService);

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

  it(`should have as title 'Product View Component'`, () => {
    const fixture = TestBed.createComponent(ProductCreateComponent);
    const app = fixture.componentInstance;
    expect(app.title).toEqual('Product Create Component');
  });
});

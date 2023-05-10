import { ComponentFixture, TestBed } from '@angular/core/testing';
import { ProductViewComponent } from './product-view.component';
import { HttpClientTestingModule, HttpTestingController } from '@angular/common/http/testing';
import { HttpClientModule } from '@angular/common/http';
import { ProductService } from 'src/app/service/product/product.service';
import { RootService } from 'src/app/service/root/root.service';
import { NgxDatatableModule } from '@swimlane/ngx-datatable';

describe('ProductViewComponent', () => {
  let component: ProductViewComponent;
  let fixture: ComponentFixture<ProductViewComponent>;
  let productService: ProductService;
  let RootService: RootService;
  let httpMock: HttpTestingController;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [ HttpClientTestingModule, HttpClientModule, NgxDatatableModule  ],
      declarations: [ ProductViewComponent ]
    })
    .compileComponents();

    fixture = TestBed.createComponent(ProductViewComponent);
    component = fixture.componentInstance;
    productService = TestBed.inject(ProductService);
    httpMock = TestBed.inject(HttpTestingController);
  });

  afterEach(() => {
    httpMock.verify();
  });

  it('should create product view', () => {
    expect(component).toBeTruthy();
  });

  it(`should have as title 'Product View Component'`, () => {
    const fixture = TestBed.createComponent(ProductViewComponent);
    const app = fixture.componentInstance;
    expect(app.title).toEqual('Product View Component');
  });
});
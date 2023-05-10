import { TestBed } from '@angular/core/testing';
import { HttpClientModule } from '@angular/common/http';
import { ProductService } from './product.service';

describe('ProductService', () => {
  let service: ProductService;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [ HttpClientModule ]
    })
    .compileComponents();
    TestBed.configureTestingModule({});
    service = TestBed.inject(ProductService);
  });

  it('should create product service', () => {
    expect(service).toBeTruthy();
  });
});

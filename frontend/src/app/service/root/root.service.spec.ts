import { TestBed } from '@angular/core/testing';
import { HttpClientModule } from '@angular/common/http';
import { RootService } from './root.service';

describe('RootService', () => {
  let service: RootService;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [ HttpClientModule ]
    })
    .compileComponents();
    TestBed.configureTestingModule({});
    service = TestBed.inject(RootService);
  });

  it('should create root service', () => {
    expect(service).toBeTruthy();
  });
});

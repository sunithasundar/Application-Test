import { ComponentFixture, TestBed } from '@angular/core/testing';

import { CsvDisplayComponent } from './csv-display.component';

describe('CsvDisplayComponentComponent', () => {
  let component: CsvDisplayComponent;
  let fixture: ComponentFixture<CsvDisplayComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ CsvDisplayComponent ]
    })
    .compileComponents();

    fixture = TestBed.createComponent(CsvDisplayComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});

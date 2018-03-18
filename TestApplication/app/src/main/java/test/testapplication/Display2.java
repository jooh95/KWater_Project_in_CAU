package test.testapplication;

import android.app.DatePickerDialog;
import android.content.Intent;
import android.os.Bundle;
import android.support.v7.app.AppCompatActivity;
import android.view.View;
import android.widget.AdapterView;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.DatePicker;
import android.widget.Spinner;
import android.widget.TextView;
import android.widget.Toast;

import com.github.mikephil.charting.charts.LineChart;
import com.github.mikephil.charting.components.Description;
import com.github.mikephil.charting.components.XAxis;
import com.github.mikephil.charting.data.Entry;
import com.github.mikephil.charting.data.LineData;
import com.github.mikephil.charting.data.LineDataSet;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.text.ParseException;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Date;
import java.util.GregorianCalendar;
import java.util.HashMap;
import java.util.List;

public class Display2 extends AppCompatActivity {

    private GregorianCalendar minDate, maxDate;
    private myCalendar startDate, endDate;
    private Spinner criteria;
    private Button moveBtn;
    private LineChart chart;

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.display2);

        chart = (LineChart) findViewById(R.id.chart);
        XAxis xAxis = chart.getXAxis();
        xAxis.setPosition(XAxis.XAxisPosition.BOTTOM);
        xAxis.setValueFormatter(new DayAxisValueFormatter(chart));
        xAxis.setGranularity(240f);
        Description description = new Description();
        description.setText("");
        chart.setDescription(description);
        chart.getLegend().setEnabled(false);

        minDate = new GregorianCalendar();
        maxDate = new GregorianCalendar();

        moveBtn = (Button) findViewById(R.id.moveBtn);
        moveBtn.setEnabled(false);

        startDate = new myCalendar(minDate, (TextView) findViewById(R.id.startDate));
        endDate = new myCalendar(maxDate, (TextView) findViewById(R.id.endDate));

        startDate.getTextView().setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                setDateDialog(startDate);
            }
        });

        endDate.getTextView().setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                setDateDialog(endDate);
            }
        });

        startDate.getTextView().setClickable(false);
        endDate.getTextView().setClickable(false);

        criteria = (Spinner) findViewById(R.id.criteria);

        setCriteriaList();

        // criteria가 선택되었을 때 기간 받아오기
        criteria.setOnItemSelectedListener(new AdapterView.OnItemSelectedListener() {
            @Override
            public void onItemSelected(AdapterView<?> parent, View view, int position, long id) {
                if (parent.getAdapter().getCount() == position || !parent.isEnabled()) {
                    return;
                }

                Intent intent = getIntent();
                HashMap<String, String> param = new HashMap<String, String>();
                param.put("idlocation", intent.getStringExtra("idlocation"));
                param.put("criteria", criteria.getSelectedItem().toString());
                String url = NetworkUtil.urlBuilder(NetworkUtil.SERVER_URL, param);

                new NetworkUtil.requestGET(Display2.this, url, new NetworkUtil.preExecutable() {
                    @Override
                    public void execute() {
                        startDate.getTextView().setClickable(false);
                        startDate.getTextView().setText("");
                        endDate.getTextView().setClickable(false);
                        endDate.getTextView().setText("");
                        moveBtn.setEnabled(false);
                        chart.clear();
                    }
                }, new NetworkUtil.postExecutable() {
                    @Override
                    public void execute(JSONObject response) {
                        try {
                            minDate.setTimeInMillis(convertStringToTime(response.getJSONObject("data").getString("first_date")));
                            maxDate.setTimeInMillis(convertStringToTime(response.getJSONObject("data").getString("last_date")));
                        } catch (JSONException e) {
                            Toast.makeText(Display2.this, Display2.this.getResources().getString(R.string.request_failed), Toast.LENGTH_SHORT).show();
                            //e.printStackTrace();
                        }

                        startDate.setDate(minDate);
                        endDate.setDate(maxDate);

                        startDate.getTextView().setClickable(true);
                        endDate.getTextView().setClickable(true);
                        moveBtn.setEnabled(true);
                    }
                }).execute();
            }

            @Override
            public void onNothingSelected(AdapterView<?> adapterView) {

            }
        });

        findViewById(R.id.button_back).setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                finish();
            }
        });

        moveBtn.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                if (endDate.getTimeInMillis() - startDate.getTimeInMillis() < 0) {
                    Toast.makeText(getApplicationContext(), Display2.this.getResources().getString(R.string.date_warning), Toast.LENGTH_SHORT).show();
                }
                else {
                    getData();
                }
            }
        });
    }

    private void setCriteriaList() {

        Intent intent = getIntent();
        HashMap<String, String> param = new HashMap<String, String>();
        param.put("idlocation", intent.getStringExtra("idlocation"));
        String url = NetworkUtil.urlBuilder(NetworkUtil.SERVER_URL, param);
        final String hint = "CRITERIA";

        new NetworkUtil.requestGET(this, url, new NetworkUtil.preExecutable() {
            @Override
            public void execute() {
                criteria.setEnabled(false);
                String[] list = {hint};
                ArrayAdapter<String> adapter = new ArrayAdapter<String>(criteria.getContext(), android.R.layout.simple_spinner_dropdown_item, list);
                criteria.setAdapter(adapter);
                criteria.setSelection(0);
            }
        }, new NetworkUtil.postExecutable() {
            @Override
            public void execute(JSONObject response) {
                ArrayList<String> arrayList = new ArrayList<String>();
                try {
                    JSONArray jArray = response.getJSONArray("data");
                    for (int i = 0; i < jArray.length(); i++) {
                        arrayList.add(jArray.get(i).toString());
                    }
                    arrayList.add(hint);
                } catch (JSONException e) {
                    Toast.makeText(Display2.this, Display2.this.getResources().getString(R.string.request_failed), Toast.LENGTH_SHORT).show();
                    //e.printStackTrace();
                    return;
                }
                SpinnerUtil.MySpinnerAdapter adapter = new SpinnerUtil.MySpinnerAdapter(Display2.this, android.R.layout.simple_spinner_dropdown_item, arrayList);
                criteria.setAdapter(adapter);
                criteria.setSelection(adapter.getCount());
                criteria.setEnabled(true);
            }
        }).execute();
    }

    private void setDateDialog(final myCalendar calendar) {
        DatePickerDialog dialog = new DatePickerDialog(this, new DatePickerDialog.OnDateSetListener() {
            @Override
            public void onDateSet(DatePicker datePicker, int i, int i1, int i2) {
                calendar.setDate(i, i1, i2);
            }
        }, calendar.getYear(), calendar.getMonth(), calendar.getDayOfMonth());
        dialog.getDatePicker().setMinDate(minDate.getTimeInMillis());
        dialog.getDatePicker().setMaxDate(maxDate.getTimeInMillis());
        dialog.show();
    }

    private void getData() {

        Intent intent = getIntent();
        HashMap<String, String> param = new HashMap<String, String>();
        param.put("idlocation", intent.getStringExtra("idlocation"));
        param.put("criteria", criteria.getSelectedItem().toString());

        GregorianCalendar tmp = new GregorianCalendar();
        tmp.set(startDate.getYear(), startDate.getMonth(), startDate.getDayOfMonth(), 0, 0, 0);
        param.put("start", convertTimeToString(tmp.getTimeInMillis()));

        tmp.set(endDate.getYear(), endDate.getMonth(), endDate.getDayOfMonth(), 0, 0, 0);
        tmp.add(GregorianCalendar.DAY_OF_MONTH, 1);
        param.put("end", convertTimeToString(tmp.getTimeInMillis()));

        String url = NetworkUtil.urlBuilder(NetworkUtil.SERVER_URL, param);

        new NetworkUtil.requestGET(this, url, null, new NetworkUtil.postExecutable() {
            @Override
            public void execute(JSONObject response) {
                long time, max = 0, min = 0;

                List<Entry> entries = new ArrayList<Entry>();
                try {
                    JSONArray jArray = response.getJSONArray("data");
                    for (int i = 0; i < jArray.length(); i++) {
                        JSONObject jObject = jArray.getJSONObject(i);

                        time = convertStringToTime(jObject.getString("timestamp")) / 60000;
                        if (i == 0) {
                            max = min = time;
                        }
                        else {
                            if (max < time)
                                max = time;
                            if (min > time)
                                min = time;
                        }
                        entries.add(new Entry((float) time, (float) jObject.getDouble("value")));
                    }
                } catch (JSONException e) {
                    Toast.makeText(Display2.this, Display2.this.getResources().getString(R.string.request_failed), Toast.LENGTH_SHORT).show();
                    //e.printStackTrace();
                    return;
                }
                XAxis xAxis = chart.getXAxis();
                xAxis.setAxisMaximum((float) (max + (max % 60 == 0 ? 0 : 60 - max % 60)));
                xAxis.setAxisMinimum((float) (min - min % 60));
                long interval = ((max + (max % 60 == 0 ? 0 : 60 - max % 60)) - (min - min % 60)) / 60;
                interval = interval / 6 + (interval % 6 == 0 ? 0 : 1);
                xAxis.setGranularity((float) interval * 60);

                LineDataSet dataSet = new LineDataSet(entries, criteria.getSelectedItem().toString());

                LineData lineData = new LineData(dataSet);
                chart.setData(lineData);
                chart.invalidate();
            }
        }).execute();
    }

    private long convertStringToTime(String time) {
        SimpleDateFormat dateFormat = new SimpleDateFormat("yyMMdd-HH:mm");
        Date date = null;
        try {
            date = dateFormat.parse(time);
        } catch (ParseException e) {
            e.printStackTrace();
        }
        return date.getTime();
    }

    private String convertTimeToString(Long time) {
        SimpleDateFormat dateFormat = new SimpleDateFormat("yyMMdd-HH:mm");
        return dateFormat.format(new Date(time));
    }

    private class myCalendar extends GregorianCalendar{

        private TextView tv;

        myCalendar(GregorianCalendar calendar, TextView tv) {
            super.setTimeInMillis(calendar.getTimeInMillis());
            this.tv = tv;
        }

        void setDate(int year, int month, int date) {
            super.set(year, month, date);
            this.setText();
        }

        void setDate(GregorianCalendar calendar) {
            super.setTimeInMillis(calendar.getTimeInMillis());
            this.setText();
        }

        int getYear() {
            return super.get(GregorianCalendar.YEAR);
        }

        int getMonth() {
            return super.get(GregorianCalendar.MONTH);
        }

        int getDayOfMonth() {
            return super.get(GregorianCalendar.DAY_OF_MONTH);
        }

        String getDateString() {
            SimpleDateFormat dateFormat = new SimpleDateFormat("yyyy-MM-dd");
            return dateFormat.format(super.getTime());
        }

        TextView getTextView() {
            return tv;
        }

        private void setText() {
            this.tv.setText(this.getDateString());
        }
    }
}

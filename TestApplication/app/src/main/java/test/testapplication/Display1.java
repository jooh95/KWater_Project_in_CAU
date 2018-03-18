package test.testapplication;

import android.content.Intent;
import android.os.Bundle;
import android.support.v7.app.AppCompatActivity;
import android.view.Gravity;
import android.view.View;
import android.widget.AdapterView;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.LinearLayout;
import android.widget.Spinner;
import android.widget.TextView;
import android.widget.Toast;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;
import java.util.HashMap;

public class Display1 extends AppCompatActivity {

    private Spinner[] location = new Spinner[4];
    private Button moveBtn;
    private String idlocation;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.display1);

        moveBtn = (Button) findViewById(R.id.moveBtn);

        location[0] = (Spinner)findViewById(R.id.location1);
        location[1] = (Spinner)findViewById(R.id.location2);
        location[2] = (Spinner)findViewById(R.id.location3);
        location[3] = (Spinner)findViewById(R.id.location4);

        for (int i = 1; i <= location.length; i++) {
            disableSpinner(i);
            location[i - 1].setOnItemSelectedListener(new OnItemSelectedListener(i));
        }

        // 초기화
        updateLocation(1);

        ((Button) findViewById(R.id.button_back)).setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                finish();
            }
        });

        // 다음 화면으로 이동
        moveBtn.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                Intent intent = new Intent(getApplicationContext(), Display2.class);
                intent.putExtra("idlocation", idlocation);
                startActivity(intent);
            }
        });
    }

    private void disableSpinner(int spinner_id) {
        Spinner target = location[spinner_id - 1];
        if (target.isEnabled()) {
            if (spinner_id == 4) {
                moveBtn.setEnabled(false);
                initCriteria();
            }
            target.setEnabled(false);
            String[] list = {"L" + spinner_id};
            ArrayAdapter<String> adapter = new ArrayAdapter<String>(this, android.R.layout.simple_spinner_dropdown_item, list);
            target.setAdapter(adapter);
            target.setSelection(0);
        }
    }

    private void initCriteria() {
        LinearLayout ll = (LinearLayout) findViewById(R.id.criteriaList);
        ll.removeAllViews();
        TextView tv = new TextView(this);
        tv.setText(Display1.this.getResources().getString(R.string.location_empty));
        tv.setGravity(Gravity.CENTER_HORIZONTAL);
        ll.addView(tv);
    }

    private void updateLocation(final int spinner_id) {

        HashMap<String, String> param = new HashMap<String, String>();
        for (int i = 1; i < spinner_id; i++) {
            param.put("L" + i, location[i - 1].getSelectedItem().toString());
        }
        String url = NetworkUtil.urlBuilder(NetworkUtil.SERVER_URL, param);

        new NetworkUtil.requestGET(this, url, new NetworkUtil.preExecutable() {
            @Override
            public void execute() {
                for (int i = spinner_id; i <= location.length; i++) {
                    disableSpinner(i);
                }
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
                    arrayList.add("L" + spinner_id);
                } catch (JSONException e) {
                    Toast.makeText(Display1.this, Display1.this.getResources().getString(R.string.request_failed), Toast.LENGTH_SHORT).show();
                    //e.printStackTrace();
                    return;
                }
                SpinnerUtil.MySpinnerAdapter adapter = new SpinnerUtil.MySpinnerAdapter(Display1.this, android.R.layout.simple_spinner_dropdown_item, arrayList);
                location[spinner_id - 1].setAdapter(adapter);
                location[spinner_id - 1].setSelection(adapter.getCount());
                location[spinner_id - 1].setEnabled(true);
            }
        }).execute();
    }

    private class OnItemSelectedListener implements AdapterView.OnItemSelectedListener {

        private int selected_id;

        OnItemSelectedListener(int selected_id) {
            this.selected_id = selected_id;
        }

        @Override
        public void onItemSelected(AdapterView<?> parent, View view, int position, long id) {
            // 초기값이 아닌 다른 값으로 변경되었을 경우에만 실행
            if (parent.getAdapter().getCount() != position && parent.isEnabled()) {
                if (selected_id < 4) { // 맨 마지막에서는 위치 업데이트가 아닌 현재 값들을 보여주기 위함
                    updateLocation(selected_id + 1);
                }
                else { // show values
                    showCurrentValue();
                }
            }
        }

        @Override
        public void onNothingSelected(AdapterView<?> parent) {

        }
    }

    private void showCurrentValue() {

        HashMap<String, String> param = new HashMap<String, String>();
        for (int i = 1; i <= location.length; i++) {
            param.put("L" + i, location[i - 1].getSelectedItem().toString());
        }
        String url = NetworkUtil.urlBuilder(NetworkUtil.SERVER_URL, param);

        new NetworkUtil.requestGET(this, url, new NetworkUtil.preExecutable() {
            @Override
            public void execute() {
                ((LinearLayout) findViewById(R.id.criteriaList)).removeAllViews();
            }
        }, new NetworkUtil.postExecutable() {
            @Override
            public void execute(JSONObject response) {
                try {
                    idlocation = response.getString("idlocation");
                    JSONArray jArray = response.getJSONArray("data");
                    for (int i = 0; i < jArray.length(); i++) {
                        JSONObject jObject = jArray.getJSONObject(i);
                        add(jObject);
                    }
                    moveBtn.setEnabled(true);
                } catch (JSONException e) {
                    Toast.makeText(Display1.this, Display1.this.getResources().getString(R.string.request_failed), Toast.LENGTH_SHORT).show();
                    initCriteria();
                    //e.printStackTrace();
                }
            }
        }).execute();
    }

    private void add(JSONObject jObject) {

        CriteriaView criteria = new CriteriaView(this);
        Double lowerBound, upperBound, value;
        String desc, unit;
        Boolean flag;

        try {
            upperBound = jObject.getDouble("upperBound");
            if (jObject.isNull("lowerBound")) {
                criteria.bound.setText(Double.toString(upperBound));
            }
            else {
                lowerBound = jObject.getDouble("lowerBound");
                criteria.bound.setText(lowerBound + " ~ " + upperBound);
            }
            desc = jObject.getString("desc");
            value = jObject.getDouble("value");
            unit = jObject.getString("unit");
            flag = jObject.getBoolean("flag");

            criteria.desc.setText(desc);
            criteria.value.setText(Double.toString(value));
            criteria.unit.setText(unit);
            criteria.flag.setText(flag ? "O" : "X");
        } catch (JSONException e) {
            e.printStackTrace();
        }

        ((LinearLayout) findViewById(R.id.criteriaList)).addView(criteria);
    }
}

package test.testapplication;

import android.content.Context;
import android.content.res.TypedArray;
import android.util.AttributeSet;
import android.view.LayoutInflater;
import android.view.View;
import android.widget.GridLayout;
import android.widget.TextView;

public class CriteriaView extends GridLayout {

    TextView bound, desc, value, unit, flag;

    public CriteriaView(Context context) {
        super(context);
        initView();
    }

    public CriteriaView(Context context, AttributeSet attrs) {
        super(context, attrs);
        initView();
        getAttrs(attrs);
    }

    public CriteriaView(Context context, AttributeSet attrs, int defStyleAttr) {
        super(context, attrs, defStyleAttr);
        initView();
        getAttrs(attrs, defStyleAttr);
    }

    private void initView() {
        String infService = Context.LAYOUT_INFLATER_SERVICE;
        LayoutInflater li = (LayoutInflater) getContext().getSystemService(infService);
        View v = li.inflate(R.layout.criteria_view, this, false);
        addView(v);

        bound = (TextView) findViewById(R.id.bound);
        desc = (TextView) findViewById(R.id.desc);
        desc.setSelected(true);
        value = (TextView) findViewById(R.id.value);
        unit = (TextView) findViewById(R.id.unit);
        flag = (TextView) findViewById(R.id.flag);
    }

    private void getAttrs(AttributeSet attrs) {
        TypedArray typedArray = getContext().obtainStyledAttributes(attrs, R.styleable.CriteriaView);
        setTypeArray(typedArray);
    }

    private void getAttrs(AttributeSet attrs, int defStyleAttr) {
        TypedArray typedArray = getContext().obtainStyledAttributes(attrs, R.styleable.CriteriaView, defStyleAttr, 0);
        setTypeArray(typedArray);
    }

    private void setTypeArray(TypedArray typedArray) {
        bound.setText(typedArray.getString(R.styleable.CriteriaView_bound));
        desc.setText(typedArray.getString(R.styleable.CriteriaView_desc));
        value.setText(typedArray.getString(R.styleable.CriteriaView_value));
        unit.setText(typedArray.getString(R.styleable.CriteriaView_unit));
        flag.setText(typedArray.getString(R.styleable.CriteriaView_flag));

        typedArray.recycle();
    }
}

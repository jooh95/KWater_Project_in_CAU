package test.testapplication;

import android.content.Context;
import android.support.annotation.NonNull;
import android.support.annotation.Nullable;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ArrayAdapter;
import android.widget.TextView;

import java.util.List;

public class SpinnerUtil {

    static class MySpinnerAdapter extends ArrayAdapter<String> {

        MySpinnerAdapter(@NonNull Context context, int resource, @NonNull List<String> objects) {
            super(context, resource, objects);
        }

        @NonNull
        @Override
        public View getView(int position, @Nullable View convertView, @NonNull ViewGroup parent) {
            View v = super.getView(position, convertView, parent);
            if (position == getCount()) {
                ((TextView) v.findViewById(android.R.id.text1)).setText("");
                ((TextView) v.findViewById(android.R.id.text1)).setHint(getItem(getCount()));
            }
            return v;
        }

        @Override
        public int getCount() {
            return super.getCount() - 1;
        }
    }
}

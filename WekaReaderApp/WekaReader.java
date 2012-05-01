/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
import java.io.*;
import weka.core.Instances;
import java.util.Random;
import weka.attributeSelection.*;
import weka.classifiers.Evaluation;
import weka.classifiers.meta.AttributeSelectedClassifier;
import weka.classifiers.trees.J48;
import weka.filters.Filter;

/**
 *
 * @author Karim Abulainine
 * @author Daniel Stankevich
 */
public class WekaReader {

    /**
     * @param args the command line arguments
     */
    public static void main(String[] args) {
        try {

            CfsSubsetEval eval = new CfsSubsetEval();
            String[] options = new String[8];
            options[0] = "-i";
            options[1] = "../data/iris.arff";
            options[2] = "-s";
            options[3] = "weka.attributeSelection.BestFirst -D 1 -N 5";
            options[4] = "-x";
            options[5] = "10";
            options[6] = "-n";
            options[7] = "1";

            //System.out.println(AttributeSelection.SelectAttributes(eval, options));

            FileWriter fstream = new FileWriter("../data/output.txt");
            BufferedWriter out = new BufferedWriter(fstream);
            out.write(AttributeSelection.SelectAttributes(eval, options));
            //Close the output stream
            out.close();

        } catch (Exception e) {
            System.out.print(e.toString());
        }
    }
}

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package WekaReaderApp;

import java.io.*;
import weka.core.Instances;
import java.util.Random;
import weka.attributeSelection.*;
import weka.classifiers.Evaluation;
import weka.classifiers.bayes.NaiveBayesUpdateable;
import weka.classifiers.meta.AttributeSelectedClassifier;
import weka.classifiers.trees.J48;
import weka.core.Instance;
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
            System.out.println("Weka Classifier");
            
            BufferedReader reader = new BufferedReader(
                    new FileReader("data/articles.arff"));
            Instances data = new Instances(reader);
            reader.close();
            // setting class attribute
            data.setClassIndex(data.numAttributes() - 1);

            String[] treeOptions = new String[1];
            treeOptions[0] = "-U";            // unpruned tree
            J48 tree = new J48();         // new instance of tree
            tree.setOptions(treeOptions);     // set the options
            tree.buildClassifier(data);

            // train NaiveBayes
            NaiveBayesUpdateable nb = new NaiveBayesUpdateable();
            nb.buildClassifier(data);
            for (int i = 0; i < data.numInstances(); i++) {
                nb.updateClassifier(data.instance(i));
            }

            Evaluation eval = new Evaluation(data);
            eval.crossValidateModel(tree, data, data.numInstances(), new Random(1));

            Instances unlabeled = new Instances(
                    new BufferedReader(
                    new FileReader("data/unlabeled.arff")));

            // set class attribute
            unlabeled.setClassIndex(unlabeled.numAttributes() - 1);

            // create copy
            Instances labeled = new Instances(unlabeled);

            // label instances
            for (int i = 0; i < unlabeled.numInstances(); i++) {
                double clsLabel = tree.classifyInstance(unlabeled.instance(i));
                labeled.instance(i).setClassValue(clsLabel);
                //System.out.println(clsLabel + " -> " + unlabeled.classAttribute().value((int) clsLabel) + " : " + print(tree.distributionForInstance(unlabeled.instance(i))));
            }
            // save labeled data
            BufferedWriter writer = new BufferedWriter(
                    new FileWriter("data/labeled.arff"));            
            
            writer.write(labeled.toString());
            System.out.println(labeled.toString());
            writer.newLine();
            writer.flush();
            writer.close();

        } catch (Exception e) {
            System.out.print(e.toString());
        }
    }
    
    public static String print(double [] array){
        String result = "";
        for(Double d : array){
            result += "["+ d + "]";
        }
        return result;
    }
}

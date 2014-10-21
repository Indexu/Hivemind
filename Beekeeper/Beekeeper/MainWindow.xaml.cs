using System;
using System.Collections.Generic;
using System.Collections.ObjectModel;
using System.Data;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Windows;
using System.Windows.Controls;
using System.Windows.Data;
using System.Windows.Documents;
using System.Windows.Input;
using System.Windows.Media;
using System.Windows.Media.Imaging;
using System.Windows.Navigation;
using System.Windows.Shapes;

namespace Beekeeper
{
    /// <summary>
    /// Interaction logic for MainWindow.xaml
    /// </summary>
    public partial class MainWindow : Window
    {
        hivemind hive = new hivemind();

        public MainWindow()
        {
            InitializeComponent();

            try
            {
                hive.connectDB();
            }
            catch (Exception ex)
            {
                MessageBox.Show(ex.ToString());
            }

            dataGrid1.ItemsSource = hive.readSqlAccounts();

        }
        //val í datagrid
        private void dataGrid1_SelectionChanged(object sender, SelectionChangedEventArgs e)
        {
            if (dataGrid1.SelectedIndex != -1)
            {
                input_accountID.Text = (e.AddedItems[0] as hivemind.Bees).accountID.ToString();
                input_email.Text = (e.AddedItems[0] as hivemind.Bees).email;
                input_status.Text = (e.AddedItems[0] as hivemind.Bees).status;

                if ((e.AddedItems[0] as hivemind.Bees).confirmed == 0)
                {
                    radio_0.IsChecked = true;
                    radio_1.IsChecked = false;
                }

                if ((e.AddedItems[0] as hivemind.Bees).confirmed == 1)
                {
                    radio_0.IsChecked = false;
                    radio_1.IsChecked = true;
                }
            }
        }

        //Update takkinn
        private void button_update_Click(object sender, RoutedEventArgs e)
        {
            string accountID = input_accountID.Text;
            string email = input_email.Text;
            string status = input_status.Text;
            int confirmed = 0;

            if (radio_0.IsChecked == true)
            {
                confirmed = 0;
            }

            if (radio_1.IsChecked == true)
            {
                confirmed = 1;
            }

            try
            {
                hive.Update(Convert.ToInt32(accountID), email, status, Convert.ToInt32(confirmed));
                dataGrid1.ItemsSource = hive.readSqlAccounts();
            }
            catch (Exception ex)
            {
                MessageBox.Show(ex.ToString());
            }
        }

        //delete takkinn
        private void button_delete_Click(object sender, RoutedEventArgs e)
        {
            string accountID = input_accountID.Text;
            
            try
            {
                hive.Delete(Convert.ToInt32(accountID));
                dataGrid1.ItemsSource = hive.readSqlAccounts();
            }
            catch (Exception ex)
            {
                MessageBox.Show(ex.ToString());
            }
        }

        //refresh takkinn fyrir Accounts tab
        private void button_refresh_Click(object sender, RoutedEventArgs e)
        {
            dataGrid1.ItemsSource = hive.readSqlAccounts();
        }
        //refresh takkinn fyrir Users tab
        private void button_refresh2_Click(object sender, RoutedEventArgs e)
        {
            dataGrid1.ItemsSource = hive.readSqlAccounts();
        }
        // New Account button
        private void Button_Click_1(object sender, RoutedEventArgs e)
        {
            newUser newuser = new newUser();
            newuser.Show();
        }   
    }
}

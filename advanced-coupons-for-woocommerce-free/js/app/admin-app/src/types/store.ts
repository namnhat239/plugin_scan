// #region [Imports] ===================================================================================================

import { ISection } from "./section";
import { ISettingValue } from "./settings";
import IStoreCreditsDashboardData, {
  IStoreCreditCustomer,
  IStoreCreditStatus,
} from "./storeCredits";

// #endregion [Imports]

// #region [Types] =====================================================================================================

export interface IStore {
  sections: ISection[];
  settingValues: ISettingValue[];
  page: string;
  storeCreditsDashboard: IStoreCreditStatus[];
  storeCreditsCustomers: IStoreCreditCustomer[];
}

// #endregion [Types]

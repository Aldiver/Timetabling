import {
  mdiMonitor,
  mdiAccountKey,
  mdiAccountEye,
  mdiAccountGroup,
  mdiShieldLock,
  mdiAccountTie,
  mdiCalendarClock,
  mdiSchool,
  mdiBookOpenPageVariant,
  mdiNumeric7Box,
  mdiNumeric8Box,
  mdiNumeric9Box,
  mdiNumeric10Box
} from "@mdi/js";

export default [
  {
    route: "dashboard.index",
    icon: mdiMonitor,
    label: "Dashboard",
  },
  {
    label: "School program",
    icon: mdiSchool,
    menu: [
      {
        route: 'gradelevel.index',
        icon: mdiNumeric7Box,
        label: 'Grade Levels'
      },
      {
        route: 'section.index',
        icon: mdiNumeric8Box,
        label: 'Sections'
      },
      {
        route: 'subject.index',
        icon: mdiNumeric9Box,
        label: 'Subjects'
      },
      {
        route: 'classday.index',
        icon: mdiNumeric10Box,
        label: 'Class Days'
      },
      {
        route: 'period.index',
        icon: mdiNumeric10Box,
        label: 'Periods'
      },
      {
        route: 'timeslot.index',
        icon: mdiNumeric10Box,
        label: 'Timeslots'
      },
    ],
  },
  {
    route: "dashboard.index",
    icon: mdiBookOpenPageVariant,
    label: "Department",
  },
  {
    route: "teacher.index",
    icon: mdiAccountTie,
    label: "teacher",
  },
  {
    route: "dashboard.index",
    icon: mdiCalendarClock,
    label: "Schedule",
  },
  {
    label: "Admin",
    icon: mdiShieldLock,
    menu: [
      {
        route: 'permission.index',
        icon: mdiAccountKey,
        label: 'Permissions'
      },
      {
        route: 'role.index',
        icon: mdiAccountEye,
        label: 'Roles'
      },
      {
        route: 'user.index',
        icon: mdiAccountGroup,
        label: 'Users'
      },
    ],
  },
];

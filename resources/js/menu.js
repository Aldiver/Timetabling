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
    route: "/dashboard",
    icon: mdiMonitor,
    label: "Dashboard",
  },
  {
    label: "Grade Level",
    icon: mdiSchool,
    menu: [
      {
        route: 'permission.index',
        icon: mdiNumeric7Box,
        label: 'Grade 7'
      },
      {
        route: 'role.index',
        icon: mdiNumeric8Box,
        label: 'Grade 8'
      },
      {
        route: 'user.index',
        icon: mdiNumeric9Box,
        label: 'Grade 9'
      },
      {
        route: 'user.index',
        icon: mdiNumeric10Box,
        label: 'Grade 10'
      },
    ],
  },
  {
    route: "/dashboard",
    icon: mdiBookOpenPageVariant,
    label: "Department",
  },
  {
    route: "instructor.index",
    icon: mdiAccountTie,
    label: "Instructor",
  },
  {
    route: "/dashboard",
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
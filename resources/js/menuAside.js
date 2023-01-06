import {
  mdiMonitor,
  mdiAccountKey,
  mdiAccountEye,
  mdiAccountGroup,
  mdiCog,
} from "@mdi/js";

export default [
  {
    route: "/dashboard",
    icon: mdiMonitor,
    label: "Dashboard",
  },
  {
    label: "User Settings",
    icon: mdiCog,
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
  {
    href: 'https://example.com/',
    icon: mdiMonitor,
    label: 'Example.com',
  }
];

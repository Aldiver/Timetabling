import {
  mdiAccountCircle,
  mdiMonitor,
  mdiGithub,
  mdiLock,
  mdiAlertCircle,
  mdiSquareEditOutline,
  mdiTable,
  mdiViewList,
  mdiTelevisionGuide,
  mdiResponsive,
  mdiPalette,
  mdiReact,
} from "@mdi/js";

export default [
  {
    route: "/dashboard",
    icon: mdiMonitor,
    label: "Dashboard",
  },
  {
    route: "/tables",
    label: "Tables",
    icon: mdiTable,
  },
  {
    href: 'https://example.com/',
    icon: mdiMonitor,
    label: 'Example.com',
  }
];

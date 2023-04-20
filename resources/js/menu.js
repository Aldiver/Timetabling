import {
    mdiMonitor,
    mdiAccountKey,
    mdiAccountEye,
    mdiAccountGroup,
    mdiShieldLock,
    mdiAccountTie,
    mdiCalendarClock,
    mdiBookOpenPageVariant,
    mdiSchool,
    mdiGroup,
    mdiTimetable,
    mdiBallot,
    mdiAccountSupervisorCircle,
    mdiNotebookEditOutline,
    mdiViewWeek,
    mdiClockTimeFourOutline,
} from "@mdi/js";

export default [
    {
        route: "dashboard.index",
        icon: mdiMonitor,
        label: "Dashboard",
    },
    {
        icon: mdiCalendarClock,
        label: "Schedule",
        menu: [
            {
                route: "classday.index",
                icon: mdiViewWeek,
                label: "Class Days",
            },
            {
                route: "timeslot.index",
                icon: mdiClockTimeFourOutline,
                label: "Timeslots",
            },
            {
                route: "period.index",
                icon: mdiCalendarClock,
                label: "Periods",
            },
            {
                route: "schoolprogram.index",
                icon: mdiTimetable,
                label: "School Program",
            },
        ],
    },
    {
        label: "Teachers",
        icon: mdiSchool,
        menu: [
            {
                route: "teacher.index",
                icon: mdiAccountTie,
                label: "Profile",
            },

            {
                route: "workload.index",
                icon: mdiMonitor,
                label: "Workloads",
            },
        ],
    },
    {
        route: "section.index",
        icon: mdiAccountSupervisorCircle,
        label: "Student Sections",
    },
    {
        label: "Options",
        icon: mdiSchool,
        menu: [
            {
                route: "department.index",
                icon: mdiGroup,
                label: "Department",
            },
            {
                route: "subject.index",
                icon: mdiNotebookEditOutline,
                label: "Subjects",
            },
            {
                route: "gradelevel.index",
                icon: mdiBallot,
                label: "Grade Levels",
            },
            {
                route: "admin.index",
                icon: mdiBookOpenPageVariant,
                label: "Admin Task",
            },
        ],
    },
    {
        label: "Settings",
        icon: mdiShieldLock,
        menu: [
            {
                route: "permission.index",
                icon: mdiAccountKey,
                label: "Permissions",
            },
            {
                route: "role.index",
                icon: mdiAccountEye,
                label: "Roles",
            },
            {
                route: "user.index",
                icon: mdiAccountGroup,
                label: "Users",
            },
        ],
    },
];

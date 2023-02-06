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
    mdiNumeric10Box,
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
                route: "schoolprogram.index",
                icon: mdiNumeric7Box,
                label: "School Program",
            },
            {
                route: "classday.index",
                icon: mdiNumeric10Box,
                label: "Class Days",
            },
            {
                route: "period.index",
                icon: mdiNumeric10Box,
                label: "Periods",
            },
            {
                route: "timeslot.index",
                icon: mdiNumeric10Box,
                label: "Timeslots",
            },
        ],
    },
    {
        route: "teacher.index",
        icon: mdiAccountTie,
        label: "Teacher Profiles",
    },
    {
        route: "section.index",
        icon: mdiNumeric8Box,
        label: "Student Sections",
    },
    {
        icon: mdiMonitor,
        label: "Timetable Generator",
    },
    {
        label: "Options",
        icon: mdiSchool,
        menu: [
            {
                route: "department.index",
                icon: mdiBookOpenPageVariant,
                label: "Department",
            },
            {
                route: "subject.index",
                icon: mdiNumeric9Box,
                label: "Subjects",
            },
            {
                route: "gradelevel.index",
                icon: mdiNumeric7Box,
                label: "Grade Levels",
            },
            {
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

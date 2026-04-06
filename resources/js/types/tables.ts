export interface Meta {
    total: number,
    current_page: number,
    last_page: number,
    limit: number,
    count: number
}
export type BaseTable = {
    id: number,
    date: string,
    last_change_date: string
}
export interface TableState<T extends BaseTable = BaseTable> {
    data: T[],
    meta: Meta | null,
    loading: boolean,
    error: TableError | null
}

export interface TableResponse<T extends BaseTable = BaseTable>
{
    data: T[],
    meta: Meta
}

export type TableError =
{
    error: string,
    message: string
}
